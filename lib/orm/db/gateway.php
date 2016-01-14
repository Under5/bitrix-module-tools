<?php

namespace WS\Tools\ORM\Db;

use WS\Tools\ORM\DateTime;
use WS\Tools\ORM\Db\Gateway\FilterNotImpossible;
use WS\Tools\ORM\Db\Gateway\Repo;
use WS\Tools\ORM\Db\Gateway\User;
use WS\Tools\ORM\Entity;
use WS\Tools\ORM\EntityCollection;
use Exception;

/**
 * ������� ����� ����� ������������� api ��������
 * 
 * ������� ���� �� �������� (����������� ������������ �������) ���� ����� ������ ����������� (������ � ���������)
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
abstract class Gateway {

    /**
     * @var array
     */
    protected $attrsToFields = array();

    /**
     * @var array [[Type, multipleFlag][..]]
     */
    protected $attrs = array();

    /**
     * @var Manager
     */
    private $dbManager;

    /**
     * ���������� ���������� ������ � �� ������.
     * @var array
     */
    private $relationAttrs = array();
    
    private $singleRelsAttrs = array();
    
    private $filters = array();

    private $usesGatewaysInGydrate = array();

    private $inCriticalArea = false;

    /**
     * @var Repo
     */
    protected $repo;
    
    /**
     * @var Repo
     */
    protected $proxy;
    
    /**
     * @var EntityAnalyzer
     */
    protected $analyzer;

    private static $simpleTypes = array(
        'integer', 'int', 'str', 'string', 'bool', 'boolean', 'float'
    );

    /**
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * ��������� ����� �� ������ ��������
     * @param string $entityClass 
     * @return Gateway
     */
    protected function getGatewayByEntityClass($entityClass) {
        $currentGateway = ltrim($this->getEntityClass(), '\\') == ltrim($entityClass, '\\');
        if ($currentGateway) {
            return $this;
        }
        return $this->dbManager->getGateway($entityClass);
    }

    private function findRelationAttrs() {
        foreach ($this->attrs as $attrName => $attrData) {
            $attrType = array_shift($attrData);
            if (in_array($attrType, self::$simpleTypes)) {
                continue;
            }
            $isSingle = array_shift($attrData);
            $relationClass = $this->analyzer->getClassName($attrType);
            if ($relationClass == '\DateTime' || is_subclass_of($relationClass, '\DateTime')) {
                continue;
            }
            if (!$relationClass) {
                throw new Exception("Class for attr `$attrName` is not find, entity {$this->getEntityClass()}");
            }
            try {
                $this->relationAttrs[$attrName] = $this->getGatewayByEntityClass($relationClass);
                $this->attrs[$attrName][0] = $relationClass;
                $isSingle && ($this->singleRelsAttrs[] = $attrName);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * ����������� ��� �������� ������� (���).
     */
    protected function construct() {}

    /**
     * @param Manager $dbManager
     * @param EntityAnalyzer $analyzer
     */
    public function __construct(Manager $dbManager, EntityAnalyzer $analyzer) {
        $this->dbManager = $dbManager;
        $this->analyzer = $analyzer;

        foreach ($this->analyzer->getParam('property') as $propertyData) {
            $name = ltrim($propertyData[1], '$');
            $this->attrsToFields[$name] = $propertyData[3] ?: $name;
            $this->attrs[$name] = array($propertyData[0], $propertyData[2] != '[]');
        }

        $this->repo = new Repo();
        $this->proxy = new Repo();
        $this->findRelationAttrs();
        $this->construct();
    }
    
    /**
     * ������� ��������� �����.
     *
     * @param string $attr
     * @return bool
     */
    protected function isRelation($attr) {
        return isset($this->relationAttrs[$attr]);
    }

    protected function isSingleRelation($attr) {
        return in_array($attr, $this->singleRelsAttrs);
    }

    /**
     * ��������� ����� ����� �� ����� ���������.
     *
     * @param string $attr
     * @return Gateway
     * @throws \Exception
     */
    protected function getGatewayByRelAttr($attr) {
        if ($this->isRelation($attr)) {
            return $this->relationAttrs[$attr];
        }
        $entityClass = $this->getEntityClass();
        throw new \Exception("Attr `$attr` is not relation for entity `$entityClass`");
    }

    /**
     * ��������� ������ ������� ��� �������� �����.
     * @return string|null
     */
    abstract protected function setupFilterClass();

    /**
     * �������� ������ �������, ������� ����� ����������� ��� �������� �����.
     *
     * @return Filter
     * @throws \Exception
     */
    public function createFilter() {
        $filterClass = $this->setupFilterClass();
        if (!is_null($filterClass)) {
            return new $filterClass;
        }
        $class = get_class($this);
        throw new \Exception("Filter class is not defined for `$class`");
    }

    /**
     * @return string
     */
    protected function getEntityClass() {
        return $this->analyzer->getClass();
    }

    /**
     * ��������� �������� ������� �� ����� � ���������� ����.
     * @param string $relationString
     * @return array
     */
    protected function explodeRelation($relationString) {
        return explode('.', $relationString, 2);
    }

    /**
     * ������� �������� ��������� �������� � �������� ���� �����.
     *
     * @param string $attr
     * @return string
     * @throws Exception
     */
    protected function getFieldByAttr($attr) {
        $field = $this->attrsToFields[$attr];
        if (is_null($field)) {
            $entityClass = $this->getEntityClass();
            throw new Exception("Call  not defined attr `$attr` for `$entityClass`");
        }
        return $field;
    }
    
    /**
     * �������������� ����� ��������� � ��� ���� api ��� ����� ���������, 
     * ������� ����� ������������ � ������� �������.
     * 
     * @param string $attr
     * @return string
     */
    protected function fieldByAttrForRequestFilter($attr) {
        return $this->fieldByAttrForRequest($attr);
    }
    
    /**
     * �������������� ����� ��������� � ��� ���� ��� ����������� � ���������� ���
     * ������.
     * @param string $attr
     * @return string
     */
    protected function fieldByAttrForRequestOrder($attr) {
        return $this->fieldByAttrForRequest($attr);
    }

    /**
     * ��������������� ����� ��������� �������� � ��� ���� ��������� � ������ ����������� ������� (�����).
     *
     * @param string $attr
     * @return string
     */
    protected function fieldByAttrForRequest($attr) {
        return $this->getFieldByAttr($attr);
    }
    
    /**
     * ��������������� ����� ��������� �������� � ��� ���� ��������� 
     * (��� ����� ������������� ���������� CDBResult) ��� ����������.
     * @param string $attr
     * @return string
     */
    protected function fieldByAttrForHydrate($attr) {
        return $this->getFieldByAttr($attr);
    }

    /**
     * �������������� �������� ��������� ��������� �� ���������� (�� ������)
     * � ������� ��������� �� ����� api (��� �������)
     *
     * @param string $attrsChain
     * @return string
     * @throws Exception
     */
    protected function fieldByAttrChainForOrder($attrsChain) {
        list($attr, $relPath) = $this->explodeRelation($attrsChain);
        $isRelation = $this->isRelation($attr);
        if (!$isRelation) {
            $attr = $attrsChain;
        }
        $field = $this->fieldByAttrForRequestOrder($attr);
        if (!is_null($relPath) && $isRelation) {
            return $field .'.'. $this->getGatewayByRelAttr($attr)->fieldByAttrChainForOrder($relPath);
        }
        if (!is_null($relPath) && !$isRelation) {
            $entityClass = $this->getEntityClass();
            throw new Exception("Call attr `$attr` as relation, but is not related for `$entityClass`");
        }
        if (is_null($relPath) && $isRelation) {
            throw new Exception("Call related sort by attr `$attr`, but field is not specified");
        }
        return $field;
    }
    
    /**
     * ���������� ������ ������� � ������ ��������
     * @param array $filterParams ��������� ������� ��� ��������
     * @param array $arFilter     ������ �������� (�� ������)
     */
    protected function addConditionToBxFilter($filterParams, & $arFilter) {
        $attr = $filterParams['attr'];
        $operator = $filterParams['operator'];
        $value = $filterParams['value'];
        $bxCond = $operator.$this->fieldByAttrForRequestFilter($attr);
        // @todo �������� ��������� �������� �� ����
        $arFilter[$bxCond] = $value;
    }

    /**
     * ���������� ���������� ��� ������ � �������� ��������� �������.
     * @param array $filterParams
     * @param string $attr
     * @param string $relationPath
     * @param array  $arRelations 
     */
    protected function addConditionToRelationFilter($filterParams, $attr, $relationPath, & $arRelations) {
        $filterParams['attr'] = $relationPath;
        $arRelations[$attr][] = $filterParams;
    }

    /**
     * ���������� ������� ��� ������� � ����������� getList
     *
     * @param array $filter
     * @return array
     * @throws Exception
     */
    protected function getProcessedFilter(array $filter = null) {
        $filterKey = md5(serialize($filter));
        if (!isset($this->filters[$filterKey])) {
            $arFilter = array();
            $arRelations = array();
            foreach ($filter as $filterItem) {
                list($itemAttr, $relPath) = $this->explodeRelation($filterItem['attr']);
                $isRelationItem = $this->isRelation($itemAttr);
                if (!empty($relPath) && !$isRelationItem) {
                    throw new Exception("Call relation error `$itemAttr` in request filter");
                }
                if (empty($relPath)) {
                    $this->addConditionToBxFilter($filterItem, $arFilter);
                    continue;
                }
                // ����������� ������� �� �����
                if ($this->addRelationConditionToFilter($filterItem, $itemAttr, $relPath, $arFilter)) {
                    continue;
                }
                $this->addConditionToRelationFilter($filterItem, $itemAttr, $relPath, $arRelations);
            }
            if (!empty ($arRelations)) {
                // ������ �� ������ � ������������� ��������� ������ � ��������
                // ��������������� ���������������
                foreach ($arRelations as $itemAttr => $filter) {
                    $bxCond = $this->fieldByAttrForRequestFilter($itemAttr);
                    $arFilter[$bxCond] = $this->getGatewayByRelAttr($itemAttr)->getKeys($filter);
                    if ($filter['operator'] != '!' && empty($arFilter[$bxCond])) {
                        throw new FilterNotImpossible();
                    }
                }
            }
            $this->filters[$filterKey] = $arFilter;
        }
        return $this->filters[$filterKey];
    }


    protected function convertOrder($order) {
        $result = array();
        foreach ($order as $attrsChain => $item) {
            $result[$this->fieldByAttrChainForOrder($attrsChain)] = $item;
        }
        return $result;
    }
    
    protected function fieldByAttrForRequestRelatedItem($attr) {
        return $this->fieldByAttrForRequest($attr);
    }
    
    /**
     * �������������� ��������� ������� ���������� �� ������ � ������� �� �����.
     * @param array $rels
     * @return array
     */
    protected function convertRelations($rels) {
        $result = array();
        foreach ($rels as $item) {
            $result[] = $this->fieldByAttrForRequestRelatedItem($item);
        }
        return $result;
    }

    /**
     * ���������� (�����������) ���������� ��� ������.
     *
     * @param array $filter
     * @param array $relations
     * @param array $order
     * @return array
     */
    protected function prepareFindParams(array $filter = array(), array $order = null, array $relations = null) {
        return array(
            $this->getProcessedFilter($filter),
            $this->convertOrder($order),
            $this->convertRelations($relations)
        );
    }

    /**
     * @param array $filter
     * @param array $order
     * @param array $relations
     * @param Pager $pager
     * @param bool $useRepo
     * @return EntityCollection
     * @throws Exception
     */
    public function find(array $filter = array(), array $order = null, array $relations = null, Pager $pager = null, $useRepo = true) {
        $collection = new EntityCollection($this->getEntityClass());
        try {
            $arParams = $this->prepareFindParams($filter, $order, $relations);
        } catch (FilterNotImpossible $e) {
            $pager && $pager->setCountElements(0);
            return $collection;
        }
        if (!is_null($pager)) {
            // @todo ��������� ������ ���������� ����� CDBResult
            $arPager = array($pager->curPage, $pager->elementsInPage);
            $pager->setCountElements($this->count($filter));
            $arParams[] = $arPager;
        }
        /* @var $result \CDBResult */
        $result = call_user_func_array(array($this, 'findEngine'), $arParams);
        $collection = new EntityCollection($this->getEntityClass());
        while ($arResult = $result->Fetch()) {
            $entity = $useRepo ? $this->hydrateWithRepo($arResult) : $this->hydrateRaw($arResult);
            $collection->addItem($entity, $entity->id);
        }
        
        if (!empty($relations)) {
            $this->fillRelationProxy();
        }
        
        return $collection;
    }

    /**
     * @param array $filter
     * @param array $order
     * @param array $relations
     * @param bool $useRepo
     * @return Entity|null
     */
    public function findOne(array $filter = array(), array $order = null, array $relations = null, $useRepo = true) {
        try {
            $arParams = $this->prepareFindParams($filter, $order, $relations);
        } catch (FilterNotImpossible $e) {
            return null;
        }
        $arParams[] = array(1,1);
        $result = call_user_func_array(array($this, 'findEngine'), $arParams);
        if (($arResult = $result->Fetch())) {
            $entity = $useRepo ? $this->hydrateWithRepo($arResult) : $this->hydrateRaw($arResult);
            if (!empty($relations)) {
                $this->fillRelationProxy();
            }
            return $entity;
        }
        return null;
    }

    /**
     * ����� ��������� �� ���������������.
     * @param array $ids [integer]
     * @return EntityCollection
     * @throws Exception
     */
    public function findByIds($ids) {
        $collection = new EntityCollection($this->getEntityClass());
        try {
            $filter = $this->createFilter();
            $arFilter = $this->getProcessedFilter($filter->in('id', $ids)->toArray());
        } catch (FilterNotImpossible $e) {
            return $collection;
        }
        $dbResult = $this->findEngine($arFilter);
        if (!$dbResult) {
            return $collection;
        }
        while ($arResult = $dbResult->Fetch()) {
            $entity = $this->hydrateWithRepo($arResult);
            $collection->addItem($entity, $entity->id);
        }
        return $collection;
    }

    /**
     * ��������� ������ �����.
     * @param array $arFilter
     * @param array $arOrder
     * @param array $relations
     * @param array $arPager
     *
     * @return \CDBResult
     */
    abstract protected function findEngine();

    /**
     * @param $entity
     */
    abstract public function remove($entity);

    /**
     * @param array $filter
     * @return bool|int
     */
    public function getSelectedRowsCount(array $filter = array()) {
        try {
            $arParams = $this->prepareFindParams($filter);
        } catch (FilterNotImpossible $e) {
            return 0;
        }
        /* @var $result \CDBResult */
        $result = call_user_func_array(array($this, 'findEngine'), $arParams);
        return $result->SelectedRowsCount();
    }

    /**
     * ��������� ���������� ����� ������ � ����� (������� ����� ������������).
     * @return array
     */
    protected function getFieldsAssoc() {
        return $this->attrsToFields;
    }

    /**
     * ���������� ��������� ������ (������ ����� ����������).
     */
    protected function fillRelationProxy() {
        while (!empty($this->usesGatewaysInGydrate)) {
            /** @var Gateway $gwRel */
            $gwRel = array_shift($this->usesGatewaysInGydrate);
            $gwRel->fillProxy();
        }
    }

    /**
     * ���������� ��������-�������� � ������� �����.
     */
    protected function fillProxy() {
        if (!$this->proxy->isEmpty()) {
            $this->findByIds($this->proxy->getKeys());
            $this->proxy->clear();
        }
    }

    /**
     * ��������� ���������� (������� ��� ����������� ��������� ��������)
     * @param string $field - ����, ������� ����������� � ���������� �������������
     * @param string $value - �������� ������� ������ ������� �� ����� ����
     * @return mixed
     */
    protected function processingFieldResultValue($field, $value) {
        return $value;
    }
    
    /**
     * ��������� �������� ��� ��������� ����������� ��������
     * @param string $attr ��� ���������
     * @param array  $gwAssoc  ������ ���������� ������� ��� ���������� ��������
     * @return mixed 
     */
    protected function getAttrValue($attr, $gwAssoc) {
        $type = $this->attrs[$attr][0];
        $field = $this->fieldByAttrForHydrate($attr);
        if (!isset($gwAssoc[$field])) {
            return null;
        }
        $value = $gwAssoc[$field];
        // ����������� �������� ��-�� �������������� ��������� ������
        $value = $this->processingFieldResultValue($this->getFieldByAttr($attr), $value);
        // ���������� � ������ ������
        if (!$this->isRelation($attr)) {
            // ���� �����
            if (ltrim($type, '\\') == DateTime::className()) {
                $value && $value = new $type($value);
            }
            return $value;
        } else {
            $this->usesGatewaysInGydrate[$attr] = $relGw = $this->getGatewayByRelAttr($attr);
            // �������� �������������/���������
            if ($this->isSingleRelation($attr)) {
                return $relGw->createProxy(array('ID' => $value));
            } else {
                $collection = new EntityCollection($type);
                foreach ($value as $relId) {
                    $collection->addItem($relGw->createProxy(array('ID' => $relId)), $relId);
                }
                return $collection;
            }
        }
    }

    /**
     * ���������� (������� ������� � ������).
     * � ������� ����������� ������ ���� ������������� ID, id.
     * �������������� ��� ����� ����������� �������� ����� ��� �����
     *
     * @param array $gwAssoc ������ ���������� ����� ����� � �������� ��.
     * @return Entity
     * @throws Exception
     */
    protected function hydrateWithRepo(array $gwAssoc) {
        $key = !empty($gwAssoc['ID']) ? $gwAssoc['ID'] : $gwAssoc['id'];
        if ($this->repo->exist($key)) {
            return $this->repo->get($key);
        }
        if ($this->proxy->exist($key)) {
            $entity = $this->proxy->get($key);
            $this->proxy->remove($key);
        } else {
            $entityClass = $this->getEntityClass();
            /** @var $entity Entity **/
            $entity = new $entityClass;
        }
        $this->hydrateRaw($gwAssoc, $entity);
        $this->repo->add($key, $entity);
        return $entity;
    }

    protected function hydrateRaw(array $gwAssoc, $entity = null) {
        $entityClass = $this->getEntityClass();
        /** @var Entity $entity */
        $entity = $entity ?: new $entityClass;
        foreach (array_keys($this->attrs) as $attrName) {
            $entity->set($attrName, $this->getAttrValue($attrName, $gwAssoc));
        }
        return $entity;
    }

    /**
     * @param array $gwAssoc
     * @return Entity
     * @throws Exception
     */
    protected function createProxy(array $gwAssoc) {
        $key = !empty($gwAssoc['ID']) ? $gwAssoc['ID'] : $gwAssoc['id'];
        if ($this->repo->exist($key)) {
            return $this->repo->get($key);
        }
        if ($this->proxy->exist($key)) {
            return $this->proxy->get($key);
        }
        $entityClass = $this->getEntityClass();
        /** @var $entity Entity **/
        $entity = $entityClass::createProxy($key);
        $this->proxy->add($key, $entity);
        return $entity;
    }

    /**
     * @param $id
     * @return Entity
     */
    public function getProxy($id) {
        return $this->createProxy(array('id' => $id));
    }

    /**
     * unset entities from repo
     */
    public function refresh() {
        if ($this->inCriticalArea) {
            throw new Exception("Gateway placed into critical area");
        }
        $this->repo->clear();
    }

    /**
     * ��������� ���������� ��������� �� �������.
     * @param array $filter
     * 
     * @return array
     */
    protected function count(array $filter = array()) {
        return count($this->getKeys($filter));
    }

    /**
     * ���������� ��������
     *
     * @param Entity $entity
     * @throws Exception
     */
    public function save(Entity $entity, $withRelations = false) {
        if ($this->proxy->exist($entity->id)) {
            return ;
        }
        if (!$entity->id) {
            $this->insert($entity, $withRelations);
            return ;
        }
        $this->update($entity, $withRelations);
    }

    /**
     * ��������� ��������� ������ (���������������) ��������.
     * @return array
     */
    public function getKeys(array $filter = array()) {
        try {
            $arFilter = $this->getProcessedFilter($filter);
        } catch (FilterNotImpossible $e) {
            return array();
        }
        $res = $this->findEngine($arFilter);
        $keys = array();
        while($item = $res->Fetch()) {
            $keys[] = $item['ID'];
        }
        return $keys;
    }

    /**
     * @param $entity
     * @param bool $withRelations
     * @return array
     * @throws Exception
     */
    protected function getFieldsForSave($entity, $withRelations = false) {
        $res = array();
        foreach (array_keys($this->attrsToFields) as $attr) {
            $value = $entity->{$attr};
            if ($value && $this->isRelation($attr) && $this->isSingleRelation($attr)) {
                $needSave = !$value->id || $withRelations;
                if ($needSave && $this->getGatewayByRelAttr($attr) instanceof User) {
                    $needSave = false;
                };
                $needSave && $this->dbManager->save(array($value));
                $value = $value->id ?: false;
            }
            if ($value && $this->isRelation($attr) && !$this->isSingleRelation($attr)) {
                $toSave = array();
                $needSave = !$this->getGatewayByRelAttr($attr) instanceof User;
                if ($needSave) {
                    foreach ($value as $valueItem) {
                        $needSave = !$valueItem->id || $withRelations;
                        $needSave && $toSave[] = $valueItem;
                    }
                }
                !empty($toSave) && $this->dbManager->save($value);
                $collection = $value;
                $value = array();
                foreach ($collection as $valueItem) {
                    $value[] = $valueItem->id;
                }
                empty($value) && $value = false;
            }
            if (ltrim($this->attrs[$attr][0], '\\') == DateTime::className()) {
                if ($value && !is_object($value) && strtotime($value)) {
                    $value = new DateTime($value);
                }
                if ($value instanceof DateTime) {
                    $value = $value->toSiteDbFormat();
                }
                !$value && $value = false;
            }
            $res[$this->getFieldByAttr($attr)] = $value;
        }
        return $res;
    }

    /**
     * @param object $entity
     * @param bool $withRelations
     */
    private function update($entity, $withRelations = false) {
        $this->inCriticalArea = true;
        $fields = $this->getFieldsForSave($entity, $withRelations);
        $this->updateRow($entity->id, $fields);
        $this->inCriticalArea = false;
    }

    /**
     * @param object $entity
     * @param bool $withRelations
     */
    private function insert($entity, $withRelations = false) {
        $this->inCriticalArea = true;
        $fields = $this->getFieldsForSave($entity, $withRelations);
        $id = $this->insertRow($fields);
        $entity->id = $id;
        $this->inCriticalArea = false;
    }

    /**
     * ���������� ����� ��
     * @param $id
     * @param $fields
     * @return mixed
     */
    abstract protected function updateRow($id, $fields);

    /**
     * ������� ����� � ���� ������
     *
     * @param $fields
     * @return mixed
     */
    abstract protected function insertRow($fields);

    protected function addRelationConditionToFilter($filterItem, $itemAttr, $relPath, & $arFilter) {
        return false;
    }
}
