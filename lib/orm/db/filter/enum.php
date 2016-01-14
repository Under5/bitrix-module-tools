<?php

namespace WS\Tools\ORM\Db\Filter;

/**
 * ������ ������ ��������� ������.
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class Enum extends Common {
    
    /**
     * ������� ������� - (������� ��� ��������� �������� ��� ���� ��������)
     * @param string $entityClass  ����� ��������
     * @param string $attribute    ��� �������� ��������
     * @return Enum
     */
    public function forEntityProperty($entityClass, $attribute) {
        return $this->addCondition($entityClass, '#', $attribute);
    }

    protected function addCondition($attr, $operator, $value) {
        if ($operator == '=') {
            $operator = '';
        }
        return parent::addCondition($attr, $operator, $value);
    }
}
