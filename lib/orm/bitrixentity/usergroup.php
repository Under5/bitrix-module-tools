<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\Entity;
/**
 * �������� "������������"
 *
 * @property integer                 $id          ID            �������������
 * @property \WS\Tools\ORM\DateTime  $date        TIMESTAMP_X   ���� ��������
 * @property string                  $active      ACTIVE        ����������
 * @property string                  $name        NAME          ������������ ������
 * @property string                  $description DESCRIPTION   ��������
 * @property string                  $sort        C_SORT        ������ ����������
 * @property string                  $code        STRING_ID     ���������� ���
 *
 * @gateway user
 * @bitrixClass CGroup
 **/
class UserGroup extends Entity {
}
