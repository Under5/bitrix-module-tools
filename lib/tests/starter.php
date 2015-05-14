<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tools\Tests;


use WS\Tools\Module;
use WS\Tools\Tests\Cases\AgentRunTestCase;
use WS\Tools\Tests\Cases\CacheTestCase;
use WS\Tools\Tests\Cases\EventManagerTestCase;

class Starter {

    const SECTION = 'WSTOOLS';

    static public function className() {
        return get_called_class();
    }

    /**
     * @return \WS\Tools\Localization
     */
    static public function getLocalization() {
        return Module::getInstance()->getLocalization('tests');
    }

    static public function cases() {
        return array(
            CacheTestCase::className(),
            EventManagerTestCase::className(),
            AgentRunTestCase::className()
        );
    }

    static private function _getLocalizationByCase ($class) {
        return static::getLocalization()->fork('cases.'.$class);
    }

    /**
     * Run module tests
     * @internal param $aCheckList
     * @return array
     */
    static public function items() {
        $points = array();
        $i = 1;
        foreach (self::cases() as $caseClass) {
            /** @var $case AbstractCase */
            $case = new $caseClass(static::_getLocalizationByCase($caseClass));
            $points[self::SECTION.'-'.$i++] = array(
                'AUTO' => 'Y',
                'NAME' => $case->name(),
                'DESC' => $case->description(),
                'CLASS_NAME' => get_called_class(),
                'METHOD_NAME' => 'run',
                'PARENT' => self::SECTION,
                'PARAMS' => array(
                    'class' => $caseClass
                )
            );
        }

        return array(
            'CATEGORIES' => array(
                self::SECTION => array(
                    'NAME' => static::getLocalization()->message('run.name')
                )
            ),
            'POINTS' => $points
        );
    }

    static public function run($params) {
        $class = $params['class'];
        $result = new Result();
        if (!$class) {
            $result->setSuccess(false);
            $result->setMessage('Params not is correct');
            return $result->toArray();
        }
        $testCase = new $class(static::_getLocalizationByCase($class));
        if (!$testCase instanceof AbstractCase) {
            $result->setSuccess(false);
            $result->setMessage('Case class is not correct');
            return $result->toArray();
        }
        $refClass = new \ReflectionObject($testCase);
        $testMethods  = array_filter($refClass->getMethods(), function (\ReflectionMethod $method) {
            return strpos(strtolower($method->getName()), 'test') === 0;
        });
        try {
            $count = 0;
            /** @var $method \ReflectionMethod */
            $testCase->init();
            foreach ($testMethods as $method) {
                $testCase->setUp();
                $method->invoke($testCase);
                $testCase->tearDown();
                $count++;
            }
        } catch (\Exception $e) {
            $result->setSuccess(false)
                ->setTrace($e->getTraceAsString());
            $message = $method->getShortName(). ', '. $e->getMessage();
            if ($e instanceof \WS\Tools\Tests\Cases\ErrorException) {
                $e->getDump() && $message .= "\ndump: \n" . var_export($e->getDump(), true);
            }
            $result->setMessage($message);
            return $result->toArray();
        }
        $testCase->close();
        return $result->setSuccess(true)
            ->setMessage(static::getLocalization()->message('run.report.completed').':'.$count."\n".static::getLocalization()->message('run.report.assertions').': '.$testCase->getAssertsCount())
            ->toArray();
    }
}