<?php

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\Entity;

/**
 * ������� ���� "������", ����������� cms bitrix
 * 
 * @property  integer  $id          ID            �������������
 * @property  integer  $propertyId  PROPERTY_ID   ������������� ��������
 * @property  string   $value       VALUE         �������� ��������
 * @property  boolean  $isDefault   IS_DEFAULT    ������� ��������� ��������, ��� �������� �� ���������
 * @property  integer  $sort        SORT          ������ ����������
 * @property  string   $externalId  EXTERNAL_ID   ������� ���
 * @property  string   $xmlId       XML_ID        ������� ���
 *
 * @gateway list
 * @bitrixClass CIBlockPropertyEnum
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class EnumElement extends Entity {

    public function setIsDefault($value) {
        if (!is_bool($value)) {
            if (in_array($value, array('Y', 'N', null))) {
                $value = $value == 'Y';
            } else {
                throw new \Exception("�������� ��������������� ������� `isDefault` �������� {".get_class($this)."} - ".  var_export($value, true));
            }
        }
        $this->_set('isDefault', $value);
        return $value;
    }
}
