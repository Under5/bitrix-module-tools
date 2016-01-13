## [������� ��������](../README.md)

## ������� ����� ��� �������

�������� ��������� ���������� [������](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=43&LESSON_ID=3436) � ������

#### ����������� ������ ������

```php
<?php

class ProjectAgent extends WS\Tools\BaseAgent {

    private $offset;
    private $step;
    
    /**
     * ������ � ������������ ������������ ���������� ������ ���������� ������ 
     *
     **/
    public function __construct($offset, $step) {
        $this->offset = $offset;
        $this->step = $step;
    }

    /**
     * ���������� ����������� ������
     **/
    public function algorithm () {
        // �������� �����������
        return array($this->offset * ($this->step + 1), $this->step + 1); // ������������ ��������� ���������� ������ � ���� ������! ($offset, $step)
    }
}

```

#### ��������� ������

������� ���������� ����� API, ������ ����� �� [������������](http://dev.1c-bitrix.ru/api_help/main/reference/cagent/addagent.php)

```php
<?php
CAgent::AddAgent(
    "ProjectAgent::run(0, 5);",           // ��� �������
    "statistic",                          // ������������� ������
    "N",                                  // ����� �� �������� � ���-�� ��������
    86400,                                // �������� ������� - 1 �����
    "07.04.2005 20:03:26",                // ���� ������ �������� �� ������
    "Y",                                  // ����� �������
    "07.04.2005 20:03:26",                // ���� ������� �������
    30);
?>
```
