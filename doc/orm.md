#### [������� ��������](../README.md)

## ORM ������ ������ � ���������� ��������

�������� ������� ��������� ������������� ��������� ������� �������� �� ������������� � ���� ��������. �� ������ ������ CMS ������� �� ����� 
���������� ORM ��� ������ � �� ����������. �� ���� ������ ����������� ������������� ���������� �������� � ������� � 
���� ������������� �������� ��� ��� ����� � ������� ������������ ������ � ������ �������. ������ ORM ��������� ������ �������� 
��������� ����������� ���������� ��� ������ � ������� � ���� ��������.

### �����������

* ����������� ������ ������ ��������� �������� ���������� ������� ������������ ��������
* ������� � ������� ������ � ������ �� ����� ����������
* ���� �������� ���������� ������� ������������ ���� ����������� ������� � ����������, ��� ��������� �������� ������������������ ������
* ������ � ���������������� ����������

### �������������

��������� ������ ������������� ��������� �������� �� ������� � �� ��������, ����� ���� ����� ���� �������
```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();

$orm = $module->orm();

$collection = $orm->createSelectRequest(\Domain\Entity\ShopNews::className())
    ->withRelations(array('author'))
    ->getCollection();

/** @var \Domain\Entity\ShopNews $item */
foreach ($collection as $item) {
    var_dump($item->author->name);
}

```

�.� ��������� ������� ��������. ��� ��������� ������ ��������� � ����������� api �������� ����������� ������� ������.

### ����������� ������� ���������� �������

������ ���������� ������� ������������ �� ������ �������������� ������. � ���������������� ���������� ������� ��������� ��������, 
� � ���� ������� �� �������� ��� �������� , � ���� ������, �������� ��������� ��������.

```php
<?php

namespace Domain\Entity;

/**
 * @property integer $id
 * @property string $name NAME
 * @property string $description DESCRIPTION
 * @property \WS\Tools\ORM\BitrixEntity\User $author
 *
 * @gateway iblockElement
 * @iblockCode shop_news
 */
class ShopNews extends \WS\Tools\ORM\Entity {
}
```

���� ������������ ����������� ������ ���������� �������. ���� ������ � �� ���������� � �������������� ������ ����������� � 
doc ����� ������. ���� ������������ ����� ����������� ```@property``` � �����.
����� ������ ���� ����������� �� �������� ```\WS\Tools\ORM\Entity```

* ```@property``` �������� �������� ������, ����� � ������ �����������: ��� (���� ����� ����� ���� ���������� ������ ```[]``` 
��� �������� ��� ����� �������������), ��� ��������, ��� �������� ��������������� �����.
���� ��� �������� ��������� � ������ ��������� ��� ����� �������� 3 �������� ����� ����������
* ```@gateway``` ����������� ����� ������ � ������� ��� ORM. � �������� ��� ������ ����� ```iblockElement```
* ```@iblockCode``` ��� ��������������� ����� �� �������� ����������������� ����������, ��� �� ������ ����� ��������� ����� 
������������ ```@iblockId```

### ����������� ������

ORM ������������ ��������� ���� ������:

* ������� ���������
* ������������
* ������ �������������
* ����
* ������

������ ����������� ������ ������:
```php
<?php

namespace Domain\Entity;

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 *
 * @property integer $id
 * @property string $name
 * @property string $description DESCRIPTION
 * @property MainNews $main MAIN_NEWS
 * @property \WS\Tools\ORM\BitrixEntity\User $author
 * @property \WS\Tools\ORM\BitrixEntity\File $detailPicture DETAIL_PICTURE
 * @property \WS\Tools\ORM\BitrixEntity\Section $section SECTION
 * @property \WS\Tools\ORM\BitrixEntity\UserGroup $group SECTION
 * @property \WS\Tools\ORM\BitrixEntity\EnumElement[] $words
 *
 * @gateway iblockElement
 * @iblockCode shop_news
 */
class ShopNews extends \WS\Tools\ORM\Entity {
}
```

�������� ������� ���������� ������� ���������� ���������� ������� ������ ���� ������������ ������� ������� ������������ ����

### ��������� ��������

������� �� ���� ������ ����� �������� ����� ��������

* ������ ��������� ����� �������������
* ����� ����������� �������

������ ��������� ������� �� ��������������:

```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();

$orm = $module->orm();

$news = $orm->getById(\Domain\Entity\ShopNews::className(), 12);
```

��������� �������� ����� ������:

```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();

$orm = $module->orm();

$newRequest = $orm->createSelectRequest(\Domain\Entity\ShopNews::className());
$collection = $newRequest
    ->equal('author.id', 100)
    ->addSort('id', 'desc')
    ->withRelations(array('author', 'detailPicture', 'colors'))
    ->getCollection();

/** @var \Domain\Entity\ShopNews $item */
foreach ($collection as $item) {
    var_dump($item->detailPicture->originalName);
    // �� ������������� ������ ���������
    /** @var \WS\Tools\ORM\EntityCollection $words */
    $words = $item->words;
    /** @var \WS\Tools\ORM\BitrixEntity\EnumElement $word */
    foreach ($words as $word) {
        var_dump($word->xmlId.' - '.$word->value);
    }
}
```

� ���������� ������� ������ ORM ������������ ������ ��� ������ ```\Domain\Entity\ShopNews```. ������ ����� ��������� ��������������� �������
��� �������� ��������� ���������

#### ������ �������

��������� ������� ������� ��� ������������� ���� ������:

```php
<?php
$newsRequest = $orm->createSelectRequest(\Domain\Entity\ShopNews::className());
```

�������� ������ ����������:

* ```equal($path, $value)``` ���������
* ```in($path, $arValues)``` �������������� � ���������
* ```inRange($path, $from, $to)``` �������������� � ���������
* ```notEqual($path, $value)``` �����������

```$path``` �������� ���������� �������� ������, ���� ���������� ������� �������� ��������� �������� ����� ��������������� ����� ```.```
������: ```"author.name"```

����������:

* ```addSort($path, $direction)``` ���������� �� �����������, $direction: asc, desc

�� ������������ ������������������ ����� ���������� � ��������� ������� ���������� ��������� ����:

* ```withRelations($relations)```

��������� ����������:

* ```getCollection()``` ��������� �������� ������� ```\WS\Tools\ORM\EntityCollection```
* ```getOne()``` ��������� ������ ���������� ������
* ```count``` ���������� ��������� ����������

### ��������, ����������, ��������

������ ������ � �������������� ��
```php
<?php
// ����� ������ � ����������� � ���� ������
$newNews = new \Domain\Entity\ShopNews();
// ���������� �������
$newNews->name = "News name";
// ��� ������ ������ �������� �������� ����� ������������ proxy
$newNews->author = $orm->createProxy(\Domain\Entity\ShopNews::className(), 12);

$orm->save(array($newNews));

// �������������� ������ ���������� �������
$savedNews = $orm->getById(\Domain\Entity\ShopNews::className(), 100);

$savedNews->name = "Another name";

$orm->save(array($savedNews));

// �������� ������ �� ���� ������
$savedNews = $orm->getById(\Domain\Entity\ShopNews::className(), 101);

$orm->remove(array($savedNews));

// ���

$orm->remove(array(
    $orm->createProxy(\Domain\Entity\ShopNews::className(), 101)
));

```

### ����������

� ���� ����� ������ ��������� ���������� ������������ ������ �������

#### ��������� ��������� ��������� ������ ����������� ���������

����� ��������� �������� ��� �������� ��� ������������� ���� � ������

```php
<?php

/** @var \WS\Tools\ORM\Db\Gateway\IblockElement $shopNewsGateway */
$shopNewsGateway = $orm->getGateway(\Domain\Entity\ShopNews::className());

$allWords = $shopNewsGateway->getEnumVariants('words');

foreach ($allWords as $word) {
    var_dump($word->value);
}

```

#### ������ � proxy ��������

������ ��������� ������������� ���������� ����� ��� ������ � ������ �������� ������������� ���������� ������� �������, ��
������������� ��������� ������� �� ���� ������ ���. � ���� ������ ����� ������������ proxy ������ ��� ������������ �����.

```php
<?php

/** @var \Domain\Entity\ShopNews $news */
$news = $orm->getById(\Domain\Entity\ShopNews::className(), 101);

$news->detailPicture = $orm->createProxy(\WS\Tools\ORM\BitrixEntity\File::className(), 840);

$orm->save(array($news));

```

#### ����������� ��������

������ ��� �������� ��������� ����������� ��������. �� ������ ���������� � ������������ ���� ```\WS\Tools\ORM\BitrixEntity```

* ```User```
* ```UserGroup```
* ```Section```
* ```EnumElement```
* ```File```

�� ����� ������������ ��� ������ � ���������� �������

#### ������ � �������� �� ������� "OR"

�� ������ ������ �����������
