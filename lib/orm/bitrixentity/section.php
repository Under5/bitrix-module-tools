<?php

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\DateTime;
use WS\Tools\ORM\Entity;

/**
 * �������� ������ ����������
 *
 * @property integer              $id             ID ������ ��������������� �����
 * @property string               $code           ������������� �������������
 * @property string               $externalId     ������� ���
 * @property string               $xmlId          ������� ���
 * @property integer              $iblockId       ID ��������������� �����
 * @property Section              $parent         ������ ��������, ���� �� ����� �� ������ ��������
 * @property DateTime             $modifiedDate   ���� ���������� ��������� ���������� ������
 * @property integer              $sort           ������� ���������� (����� ����� ������ ����� ������-��������)
 * @property string               $name           ������������ ������
 * @property string               $isActive       ���� ���������� (Y|N)
 * @property string               $isGobalsActive ���� ����������, �������� ���������� ����������� (������������) ����� (Y|N). ����������� ������������� (�� ����� ���� ������� �������)
 * @property File                 $picture
 * @property string               $description    �������� ������
 * @property string               $descriptionType ��� �������� ������ (text/html)
 * @property integer              $leftMargin     ����� ������� ������. ����������� ������������� (�� ��������������� �������)
 * @property integer              $rightMargin    ����� ������� ������. ����������� ������������� (�� ��������������� �������)
 * @property integer              $depthLevel     ������� ����������� ������. ����������� ������������� (�� ��������������� �������)
 * @property string               $pageUrl        ������ URL-� � �������� ��� ���������� ��������� �������. ������������ �� ���������� ��������������� �����. ���������� �������������
 * @property User                 $modifiedBy     ������������, � ��������� ��� ���������� �������
 * @property DateTime             $dateCreate     ���� �������� ��������
 * @property User                 $createdBy      ������������, ��������� �������
 * @property File                 $detailPicture  �������� ���������� ���������
 *
 * @gateway common
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class Section extends Entity {
}
