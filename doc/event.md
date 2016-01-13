## [������� ��������](../README.md)

## �������. ���������, �����
��������� ���������� ������ � ������� ����� ������� ����� �� ������������ � ���������� ���������� ������� ���������� �����.
�� ����� �������, ��� �������� ������� �� ��������� � �������� �������� ������ ���������� ����� ���������� ����� ������� �������.
� ������� �������� ��������� ����������� ��� ������ ������, ���������� ���� �������� ������� ��� ��������� ������ ������ � ����� �������� ���������.
��� `WS\Tools` ������ � ��������� ���������� ��� ������ ���������� ��������� ������� `\WS\Tools\Events\EventsManager`. ��������� ������� ��������� ��� �����, ���:

1. �������� ������������ �� ������� �������
2. ����� �������� ������� � ����������� (������ ����������� ����� 1�-�������)

������ � ��������� �������
```php
<?php
CModule::IncludeModule('ws.tools');
$toolsModule = WS\Tools\Module::getInstance();
$eventManager = $toolsModule->eventManager();
```
����������� ����������� ������ � ������� �� ������ (����������� ������)

####1. ���� �������
��� ������� ������������ ������� *\WS\Tools\Events\EventType*.
���� ������� �������� ������, ���������� � �������� �������� ���������� � ���� ��������, � ������� `\WS\Tools\Events\EventType::MAIN_PROLOG`  
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_PROLOG);
```

��� ������������� ������ ����� �������, ���������� ��������� ��� ������ � ��� ������� ��� ������������� �������, � ������ *createByParams*  
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType = \WS\Tools\Events\EventType::createByParams("main", "OnPrologBefore");
$eventManager->subscribe($eventType, function ($arg1) {
    // ��� �����������
});
```

####2. �����������
��������� ��� ������������ ����������� ������� "OnProlog" ������ "main"
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_PROLOG);
$eventManager->subscribe($eventType, function ($arg1) {
    // ��� �����������
});
```

##### ���� ������������

��� ������� ��������� ������� ����� ������������ ��������� ���� ������������

######1. ����������� ����������� �������
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType  = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_END_BUFFER_CONTENT);
// named function
function __callWs(& $content) {
	$content = 'callWs';
}
$em->subscribe($eventType, '__callWs');
```

######2. ����������� ���������� �������
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType  = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_END_BUFFER_CONTENT);
// closure function
$em->subscribe($eventType, function (& $content) {
    $content = 'Closure';
}));
```

######3. ����������� ������������ ������ ������
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType  = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_END_BUFFER_CONTENT);
//class static method
abstract class SomeClass {
	static public function callWs(& $content) {
		$content = __METHOD__;
	}
}
$em->subscribe($eventType, array('SomeClass', 'callWs'));
```

######4. ����������� ������� ������������ ������ �����������
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType  = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_END_BUFFER_CONTENT);
class MyHandler extends \WS\Tools\Events\CustomHandler {
	public function processReference(& $content) {
		$content = __METHOD__;
	}
}
$em->subscribe($eventType, new MyHandler());
```

���

```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_PROLOG);
class MySimpleHandler extends \WS\Tools\Events\CustomHandler {
    protected function log() {
        $toolsModule->getLog('PROLOG_START');
    }
	public function process() {
	    $this->log('prolog start ' . time());
	}
}
$em->subscribe($eventType, new MySimpleHandler());
```
**��������** ������ ������ ��������� � ��������� ������, ����������� ������� ������������ ������ ��� �����������

###### �������� ������������ ������ �����������
����� ����������� ������ ��������� � ����� �������� �������� ������� ������� � ���� �������������� �� `\WS\Tools\Events\CustomHandler`

```php
<?php
class MyHandler extends \WS\Tools\Events\CustomHandler {
    private $_iblockId;
    /**
     * ����� ���������� ���������������� ������ �����������
     **/
    public function identity() {
        $params = $this->getParams();
        $iblockId = $this->params[0];
        if ( != IBLOCK_NEWS) {
            return false;
        }
        $this->_iblockId = $iblockId;
        return true;
    }
	public function process() {
	    // handle process iblock 
	}
}
```

������ ������ ����������� � ����������� ������������� �� ������: 
```php
<?php
class MyHandler extends \WS\Tools\Events\CustomHandler {
    /**
     * ����� ������������ ����� � ���������� ���������� �������� ������ �� ������ 
     **/
	public function processReference(& $content) {
		$content = str_replace('#MARK#', date('Y-m-d'), $content);
	}
}
```

```���������: �� ������ �������� ��������� ������ ������ �������� � ������ processReference, ��� ���� ����� processReference ����������� ���������� �������� � ���������� ������� ��������� �� ������```

####3. ����� �������
����� ������� "OnProlog" ������ "main", ��� �� ����� ������� ��� ����������� ������������������ �� ����� ������ `WS\Tools`
```php
<?php
$eventManager = $toolsModule->eventManager();
$eventType = \WS\Tools\Events\EventType::create(\WS\Tools\Events\EventType::MAIN_PROLOG);
$eventManager->trigger($eventType);
```
## [������� ��������](../README.md)
