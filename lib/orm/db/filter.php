<?php

namespace WS\Tools\ORM\Db;

/**
 * ������� ����� ������������� �������� � ���� ������
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
abstract class Filter implements IFilter {

    private $data = array();

    public static function className() {
        return get_called_class();
    }

    /**
     * ���������� ������� � ������
     * @param string $attr     ��� ��������� (��� ���� �� ����������)
     * @param string $operator �������� (� ������� ��������)
     * @param mixed $value     �������� �������
     * @return Filter
     */
    protected function addCondition($attr, $operator, $value) {
        $this->data[$operator.$attr] = array(
            'attr' => $attr,
            'operator' => $operator,
            'value' => $value
         );
        return $this;
    }
    
    public function equal($attr, $value) {
        return $this->addCondition($attr, '=',$value);
    }
    
    public function notEqual($attr, $value) {
        return $this->addCondition($attr, '!', $value);
    }
    
    public function less($attr, $value) {
        return $this->addCondition($attr, '<', $value);
    }
    
    public function more($attr, $value) {
        return $this->addCondition($attr, '>', $value);
    }
    
    public function lessOrEqual($attr, $value) {
        return $this->addCondition($attr, '<=', $value);
    }
    
    public function moreOrEqual($attr, $value) {
        return $this->addCondition($attr, '>=', $value);
    }
    
    public function in($attr, $values) {
        return $this->equal($attr, $values);
    }
    
    public function notIn($attr, $values) {
        return $this->notEqual($attr, $values);
    }

    public function toArray() {
        return $this->data;
    }
}
