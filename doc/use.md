## [������� ��������](../README.md)

## ������������� �����������
����� �������������� ����������� ���������� �������� � ���, ��� ������ ������������������
```php
<?php
CModule::IncludeModule('ws.tools');
```

����� ����� ���������� �������� ��� ������ ������
### ����� ������, ����� ������
��� ������� ������, ����� ��� ��������� ������� � �������� �������, �������� ����� ������� ������ "��������" ������, �������� ����� �����
```php
<?php
CModule::IncludeModule('ws.tools');
$toolsModule = WS\Tools\Module::getInstance();
```

������ �� ���� �������� ���������� ����� ����� ������ *getService($name)*
```php
<?php
CModule::IncludeModule('ws.tools');
$toolsModule = WS\Tools\Module::getInstance();
$classLoader = $toolsModule->getService('classLoader');
```

### ������������ ������������� ������ � ����������

```php
<?php

// init config
$wsTools = WS\Tools\Module::getInstance();
$wsTools->classLoader()->configure(require __DIR__ . '/autoload.php');
$config = array(
    // ����������� ��������
    "services" => array(
      // �������� �������
      'local' => array(
          // ����� �������
          'class' => \Domain\Local::className(),
          // ���������
          'params' => array(
              'dir' => __DIR__.DIRECTORY_SEPARATOR.'langs'.DIRECTORY_SEPARATOR.LANGUAGE_ID
          )
      )
    )
);
$wsTools->config($config);
```

���� ��� ���������� ��������� � ���� ������������� �������, ������ ��� ```init.php```
