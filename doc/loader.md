## [������� ��������](../README.md)

### �������� �������
�������������� �������� ������� ���������� ����� ����������� �������� �������� ������� � ���������� ������. 
������������ ���������� �������. � ���������� � ������ `autoload` ����������� ������������ ��� ��������� ������������
�������.
```php
<?php

$toolsModule = WS\Tools\Module::getInstance();
$toolsModule->classLoader()->configure(
    array(
       "psr4" => array(
           "Local\\" => __DIR__ . DIRECTORY_SEPARATOR . "lib",
           "Multiply\\Space\\Dirs\\" => array(
               __DIR__ . "/lib2",
               __DIR__ . "/lib3"
           )
           // ...
       ),
       "psr0" => array(
           "Old\\Namespaces\\" => __DIR__ . "/vendor",
           // ...
       )
   )
);
$toolsModule->config($config);
```
��� ��������� ���������� ���� ����������� ����� *classLoader*
```php
<?php
CModule::IncludeModule('ws.tools');
$toolsModule = WS\Tools\Module::getInstance();
$classLoader = $toolsModule->classLoader();
$classLoader->getDriver('psr4')->registerPathByNamespace(__DIR__ . '/local/lib', "Local\\");
```
��������, ��� ��� ������ ������� ��������������� ������� `__DIR__ . '/local/lib'`

�� ������ ������ �������������� ��������� �������� ��� ������������:
* [PSR-0](http://www.php-fig.org/psr/psr-0/ru/)
* [PSR-4](http://www.php-fig.org/psr/psr-4/ru/)
