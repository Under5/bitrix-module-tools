<?php
return array(
    'run' => array(
        'name' => '������� �������. ����������� ������������',
        'report' => array(
            'completed' => '������� ��������',
            'assertions' => '��������'
        )
    ),
    'cases' => array(
        \WS\Tools\Tests\Cases\CacheTestCase::className() => array(
            'name' => '������������ ����������� �����������',
            'description' => '',
            'errors' => array(
                'number of links to the information block and the information block entries must match' => '���������� ������ �� ���������� � ������� ���������� ������ ���������',
                'number of links on the properties of information blocks and records must match' => '���������� ������ �� ��������� ���������� � ������� ������ ���������',
                'number of links to information block sections and records must match' => '���������� ������ �� �������� ���������� � ������� ������ ���������',
            )
        )
    )
);