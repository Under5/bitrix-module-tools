<?php

namespace WS\Tools\ORM\Db\Gateway;

use WS\Tools\ORM\Db\Gateway;

/**
 * �������� ���������������� ������ ������ �����.
 * 
 * � ������������ ���������� ������� ����� ��������.
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class Common extends Gateway {

    protected function findEngine() {
        list($arFilter, $arOrder) = func_get_args();
        $bxClass = $this->analyzer->getParam('bitrixClass');
        $bxClass = $bxClass[0];
        /* @var $dbRes \CDBResult */
        $dbRes = call_user_func(array($bxClass, 'getList'), $arOrder, $arFilter);
        return $dbRes;
    }

    protected function setupFilterClass() {
        return \WS\Tools\ORM\Db\Filter\Common::className();
    }

    /**
     * ���������� ����� ��
     *
     * @param $id
     * @param $fields
     * @return mixed
     */
    protected function updateRow($id, $fields) {
        $bxClass = $this->analyzer->getParam('bitrixClass', 1);
        $bxObject = new $bxClass;
        $res = $bxObject->Update($id, $fields);
        if (!$res) {
            throw new SaveException($bxObject->LAST_ERROR);
        }
    }

    /**
     * ������� ����� � ���� ������
     *
     * @param $fields
     * @return mixed
     */
    protected function insertRow($fields) {
        $bxClass = $this->analyzer->getParam('bitrixClass', 1);
        $bxObject = new $bxClass;
        $res = $bxObject->Add($fields);
        if (!$res) {
            throw new SaveException($bxObject->LAST_ERROR);
        }
        return $res;
    }

    /**
     * @param $entity
     */
    public function remove($entity) {
        $bxClass = $this->analyzer->getParam('bitrixClass', 1);
        $bxObject = new $bxClass;
        $bxObject->Delete($entity->id);
        $entity->id = null;
    }
}
