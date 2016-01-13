#### [������� ��������](../README.md)

## ����������� ������
������������� ��������� ����������� �� ������� �������� ������� ��������� ��������� �������������.
�������� ����������� ���������� ���������� ������ ��� ����� ������ ����� `\Bitrix\Main\Data\Cache::startDataCache` � (��������!) ����� �����������.
�� ����� ���� �� ��������� ���� �� �������������� ��� � ���� ��� �� ��������� �������� ����������� ������. 
��� �� ����� ������������������� ������ ������ ��� ������������� � ����. ����� ������ ����������� ����������� ����������� ������ ��� ����� �������������� ����������� ������.
`WS\Tools` ���������� ���������� � ����� �������� ����� ����������� ������. 

������ (����������� ������� ������):

```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
// �������� �����������
$cm = $module->cacheManager();

//������ � ���
$arrayWriteCache = $cm->getArrayCache('key_cache', 3000 /* cache time */);
$arrayWriteCache->set(array(
	'one', 'two', 'tree'
));

// ��������� ������ 
$arrayReadCache = $cm->getArrayCache('key_cache', 3000);
var_dump($arrayReadCache->get() == array('one', 'two', 'tree'));
```

����������� ������:

```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
// �������� �����������
$cm = $module->cacheManager();
// ��������� ������� ����������� ������
$recordCache = $cm->getContentCache('testKeyCache', 200);
// �������� ������������ ������ ����
if ($recordCache->isExpire()) {
    // � ������ ���� ������ �� ���������, ������������ ������ ����� ������
    $recordCache->record();
    echo "1222";
    // ���������� � �������� ������ ������ � �����
    $recordCache->save(false);
}
// ����� � �����
echo $gettingCache->content();
```

��� ���������� ����������� ������������ ��� ������: `\WS\Tools\Cache\ContentCache` � `\WS\Tools\Cache\ArrayCache`.
����� `\WS\Tools\Cache\ContentCache` ���������� ������������ ��� ���������� ������ ������ ������, �� �������� ����� ����� ��������� ����������� `\WS\Tools\Cache\CacheManager::getContentCache`.
����� `\WS\Tools\Cache\ArrayCache` ������������ ��� ���������� ������� ������, �������� ����� ����� `\WS\Tools\Cache\CacheManager::getArrayCache`.

��� ��������� �������� ����������� ���������� ������� ���������:

* `$key` - ���� �����������
* `$timeLive` - ����� ����� ����

#### �������� ������� ������� �����������

###### ����� ������
* `setBxInitDir($value)` �����, � ������� �������� ��� ����������, ������������ /bitrix/cache/ (��������������) [���������..](http://dev.1c-bitrix.ru/api_help/main/reference/cphpcache/initcache.php)
* `setBxBaseDir($value)` ������� ���������� ����. �� ��������� ����� cache (��������������) [���������..](http://dev.1c-bitrix.ru/api_help/main/reference/cphpcache/initcache.php)
* `clear()` ������� ��������� ����
* `isExpire()` �������� ������������ ��������� ����

###### ArrayCache
* `get()` ��������� ������ ��������� ����
* `set($array)` ���������� ������ � ���

###### ContentCache
* `record()` ������ ������ �����������
* `abort()` ���������� ������ �����������, ������ ������ ������������ � �����
* `stop($output = false)` ��������� ������ ����������� � ������, `$output` - �������� ����������� �� ������������� ����������� ������ � ����� ������, �� ��������� �� ���������������
* `content()` ��������� ������ ���� ����� ������

#### [������� ��������](../README.md)
