<?php

namespace WS\Tools\ORM\Db\Gateway;

/**
 * ����������� �������� ���������
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class Repo {
    
    private $collection = array();

    /**
     * ���������� ������� � �����������.
     *
     * @param string $key
     * @param mixed $object
     * @throws \Exception
     */
    public function add($key, $object) {
        if ($this->exist($key)) {
            throw new \Exception("Element `$key` was used before");
        }
        $this->collection[$key] = $object;
    }

    /**
     * ��������� ������� �� �����������.
     *
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key) {
        if (!$this->exist($key)) {
            throw new \Exception("Element `$key` not exist");
        }
        return $this->collection[$key];
    }

    /**
     * �������� ������� �� �����������.
     *
     * @param string $key
     * @throws \Exception
     */
    public function remove($key) {
        if (!$this->exist($key)) {
            throw new \Exception("Element `$key` not exist");
        }
        unset($this->collection[$key]);
    }
    
    /**
     * ������� ������������� ������� � �����������.
     * @param string $key
     * @return boolean
     */
    public function exist($key) {
        return isset($this->collection[$key]);
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return empty($this->collection);
    }

    public function clear() {
        $this->collection = array();
    }

    /**
     * @return array
     */
    public function getKeys() {
        return array_keys($this->collection);
    }
}
