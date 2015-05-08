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
                'cache must be not expire' => '��� ������ ���� �� ��������',
                'cache must be not empty' => '��� ������ ���� �� ������',
                'bad stored data' => '�������� ���������� �� �����',
                'cache must be expire' => '��� ������ ���� ��������',
                'data must be empty' => '������ �� ������ ���� �������',
                'string not equals expected' => '������ �������� �� ������������� ���������',
            )
        )
    )
);