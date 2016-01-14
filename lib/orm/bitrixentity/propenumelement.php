<?php

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\Entity;

/**
 * ������� ���� "������", ����������� cms bitrix
 * 
 * @property integer $id          ID            �������������
 * @property integer $propertyId  USER_FIELD_ID ������������� ��������
 * @property string  $value       VALUE         �������� ��������
 * @property boolean $isDefault   DEF           ������� ��������� ��������, ��� �������� �� ���������
 * @property integer $sort        SORT          ������ ����������
 * @property string  $xmlId       XML_ID        ������� ���
 *
 * @gateway list
 * @bitrixClass CUserFieldEnum
 *
 */
class PropEnumElement extends Entity {
}
