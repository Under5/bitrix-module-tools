<?php

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\Entity;

/**
 * �������� ���� File
 *
 * @property integer     $id                ID              �������������
 * @property string      $name              FILE_NAME       ��� �� ����� �������
 * @property string      $description       DESCRIPTION     ��������
 * @property string      $originalName      ORIGINAL_NAME   ��� �� ������� �������� �� ������
 * @property \DateTime   $modificationDate  TIMESTAMP_X     ���� ���������
 * @property integer     $height            HEIGHT          ������ (���� �����������)
 * @property integer     $width             WIDTH           ������ (���� �����������)
 * @property integer     $size              FILE_SIZE       ������ � ������
 * @property string      $type              CONTENT_TYPE MIME ���
 * @property string      $subdir            SUBDIR          ���������� � ������� ��������� ���� �� �����
 *
 * @gateway file
 * @bitrixClass CFile
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class File extends Entity {

    public function getSrc() {
        $arFile =  \CFile::GetFileArray($this->id);
        return $arFile['SRC'];
    }

    public function sizeFormatted() {
        return \CFile::FormatSize($this->size);
    }
}
