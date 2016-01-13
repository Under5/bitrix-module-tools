#### [������� ��������](../README.md)

## ��������� �������� �������� ���������

� �������� ��������� ���������� ����������� � �������� ������� ��������� ```CEvent::Send```, ����� ���������� ��������� ��������
����� ������ ����� ���������� � �� ��������� ����� ����� (SITE_ID) � �� ����������������� ���������� (LANGUAGE_ID). ��� ���� ������� ����������
�������������� �������� ��������� (��� � �����������).  ������ ������� ```��������``` ����������� ���:

```php
<?php

$rsSites = CSite::GetList($by="sort", $order="desc", Array());
$arSites = array();
while ($arSite = $rsSites->Fetch()) {
    $arSites[] = $arSite['ID'];
}

CEvent::Send(
    "NEW_USER",
    $arSites,
    array(
	'USER_ID' => 15,
	'LOGIN' => 'ivanov',
	'EMAIL' => 'ivanov@gmail.com',
	'NAME' => '�������',
	'LAST_NAME' => '������',
	'USER_IP' => '123.456.234.55',
	'USER_HOST' => '',
    )
);

```

```WS-Tools``` �������� ������������� � ��������� �������� �������� ������������. �������� ���������� ���������� ����������� �������� �����.

```php
<?php

$module = \WS\Tools\Module::getInstance();

// �������� ������
$mail = $module->mail();

// �������� �������
$newUserPackage = $mail->createPackage("NEW_USER");

// ���������� ������� �������
$newUserPackage->setFields(array(
	'USER_ID' => 15,
	'LOGIN' => 'ivanov',
	'EMAIL' => 'ivanov@gmail.com',
	'NAME' => '�������',
	'LAST_NAME' => '������',
	'USER_IP' => '123.456.234.55',
	'USER_HOST' => '',
));

// �������� ������� ����� �������� ������
$mail->send($newUserPackage);
```
