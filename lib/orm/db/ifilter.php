<?php

namespace WS\Tools\ORM\Db;

/**
 * ��������� ������������� ������� �������.
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
interface IFilter {
    /**
     * �������� $attr, ����� $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function equal($attr, $value);
    
    /**
     * �������� $attr, �� ����� $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function notEqual($attr, $value);

    /**
     * �������� $attr, ������ $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function less($attr, $value);

    /**
     * �������� $attr, ������ $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function more($attr, $value);

    /**
     * �������� $attr, ������ ��� ����� $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function lessOrEqual($attr, $value);

    /**
     * �������� $attr, ������ ��� ����� $value
     * @param string $attr
     * @param mixed $value
     * @return IFilter
     */
    public function moreOrEqual($attr, $value);

    /**
     * �������� $attr, ��������� �� ��������� $values
     * @param string $attr
     * @param array $values
     * @return IFilter
     */
    public function in($attr, $values);

    /**
     * �������� $attr, �� ��������� �� ��������� $values
     * @param string $attr
     * @param array $values
     * @return IFilter
     */
    public function notIn($attr, $values);

    /**
     * �������� $attr, ���������� � ��������� �� $from �� $to
     * @param string $attr
     * @param mixed $from
     * @param mixed $to
     * @return IFilter
     */
    public function inRange($attr, $from, $to);

    /**
     * �������� $attr, �� ���������� � ��������� �� $from �� $to
     * @param string $attr
     * @param mixed $from
     * @param mixed $to
     * @return IFilter
     */
    public function notInRange($attr, $from, $to);

}
