## [������� ��������](../README.md)

## ����������� ������ � ��������� ����������

������� ���������� ������ �������� ��� ����������� �������� ������������� ����, ����� ����� ���������� ����������� � ���� ������ ������,
������ �������� �� ����� ����� ���������� ������ ��������� ��������, ����� �������� ������������ ���������. ��������� �� ������������� [������-�������������� �����������](https://ru.wikipedia.org/wiki/%D0%A1%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D0%BE%D1%80%D0%B8%D0%B5%D0%BD%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%BD%D0%B0%D1%8F_%D0%B0%D1%80%D1%85%D0%B8%D1%82%D0%B5%D0%BA%D1%82%D1%83%D1%80%D0%B0)

#### 1. ������ � �������

������ � ������� ������������� ��� ������ ������ ������ `\WS\Tools\Module::getService($name)`, `$name` - ��� �������.
� ��������� ������� ����� ������� ������ � ������� ����������� ����� ����.

```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
$fileLog = $module->getService('myFileLog');
$fileLog->put('error message');
```

#### 2. ����������� �������

������ ����� ���������� ����� ���������:

1. �������� ������� � ����������� � ������������� � ���������
2. ������������ ����������� �������

##### ��������� ������������ ����������� ��������:
� ����� ������������ ������ ������� ������������ ����������� � ������ `services`.
��������� ����������� �������� ������� �� �������������� �������, ��� ����� - ����� ��������, �������� - ��������� ������������� ��������.
��������� ������������� �������� ������ ��:

* `class` - ��� ������ �������
* `params` - ��������� ������������ ��������, ����� �������� ��� �������������
* `depends` - ����������� �� ������ �������� 

��� ������������ ����������� ������� ���������� �������� ������ `ServiceLocator`:
```php
<?php
CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
$serviceLocator = $module->getServiceLocator();
$serviceLocator->set('myFileLog', array(
    'class' => 'LogToFile',
    'path' => __DIR__.'/dump.log',
    ''
), array('user'));
```

##### ������ ������������� �������
��������� ����� �����, ������� ����� ������������ � �������� �������:
```php
<?php
class LogToFile {
    /**
     * ������� ������������� ������������ ����� CurrentUser
     * @var CurrentUser
     **/
    protected $user;
    
    private $_path;

    public function __construct($path) {
        $this->_path = $path;
    }
    
    public function put($content) {
        file_get_contents($this->_path, array(time(), $this->user->name, $content));
    }
}
```

������������ � �������������� ������:
```php
<?php
$config = array(
    // ��������� �������� � ����������� � �������������
    'services'=> array(
        'myFileLog' => array(
            'class' => 'LogToFile',
            'params' => array(
                'path' => __DIR__.'/dump.log'
            ),
            'depends' => array('user')
        ),
        'user' => array(
            'class' => 'CurrentUser'
        )
    )
);

CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
$module->config($config);
```

������ � �������� � ����������:
```php
<?php
$fileLog = $module->getService('myFileLog');
$fileLog->put('error message');
```

#### 3. ������ � �������� `ServiceLocator`
������ � �������� `ServiceLocator` �������� ����� ������������ � �������� ��� ������������� ��������-�����, �.�. �� �������
������� �� ����� �������� ��� ����������� ������� ��������� ��������.

��������� ������� �������������� `ServiceLocator` ��������� �������:
```php
<?php
$serviceLocator = $module->getServiceLocator();
$log = $serviceLocator->createInstance('log');
```

������ �������:

* `set($name, $params = array(), $depends = array())` - ������������ ��������� ���������� ������������� `$params` ������� $name � ������������� �� �������� `$depends`.
* `get($name)` - ��������� ������� `$name`
* `willUse($name, $object)` - ��������� ������� `$object` ��� ����������� ������������� � �������� ������� `$name`
* `createInstance($name, $params = array(), $depends = array())` - �������� ���������� ������� � �������������� ���������� � ������������.
������ �� ����� ��������������� � `ServiceLocator` ��� ���������� �������������. �� �� ����� ���������� ����������� ������������ ��� �������������.

#### ��������� ������������
��������� ������������ ��������� ����� ��������� ���������������� ������� �������� ������� ������ ����������� �������� ����������,
��������� ������ ����������� � �������� ����������� ����. ��������� � ������ ��������� ������������ ����� ��������� � [������](http://vladimirsblog.com/laravel-dependency-injection-for-beginners/).

#### ��� ������������� ���� ������ ������� � ��������� ��� ����������� � ������?
��� ������������� ������� `ServiceLocator` ����� ��� ��������� ����������� � ���������� ��������� �� ��������� ���������:
1. ��� ���������� ����� ��������� ��� ������� � ������������ ������
2. ��� ���������� ������ ������� � ����������� ��������� ������������ ������
3. ��� ���������� ������� ������. �������� ������ ���� `protected` ��� `public`. � `private` �������� ��������� �� ����������

������ ������������ ��� ������������� ����������:
```php
<?php

// ��������� ��� ��������
$config = array(
    'services' => array(
        'db' => array(
            'class' => 'DateBase',
            'params' => array(
                'host' => 'localhost',
                'user' => 'user',
            ),
            'depends' => array('shell')
        ),
        'shell' => array(
            'class' => 'CommandLineShell',
            'params' => array(
                'rootDir' => __DIR__.'/../root'
            )
        )
    )
);

// ������ ��������
 
class DateBase {
    
    /**
     * � ��� �������� ����� ������� ������ CommandLineShell
     * @var CommandLineShell
     **/
    protected $shell;
    
    /**
     * � ��������� �������� ��������� �� ������������
     **/
    private $connectionParams;
    
    public function __construct($host, $user) {
        $this->_connectionParams = array(
            'host' => $host,
            'user' => $user
        );
    }
    
    /**
     * ����� �������������������� Shell ��������
     **/
    public function getShell() {
        return $this->shell;
    }
}

class CommandLineShell {

    /**
     * ��� ������������� ���������� ���������� ��������������
     **/
    protected $rootDir;
    
    public function getRootDir() {
        return $this->rootDir;
    }
}

CModule::IncludeModule('ws.tools');
$module = \WS\Tools\Module::getInstance();
$module->config($config);

/* @var DateBase */
$db = $module->getService('db');

$db->getShell()->getRootDir(); // /var/www/project/local/root

```

�� ������� ���� �����, ��� ������� ���������������� ������������� � �������� ���������� �����������.

## [������� ��������](../README.md)
