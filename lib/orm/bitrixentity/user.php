<?php

namespace WS\Tools\ORM\BitrixEntity;

use WS\Tools\ORM\Entity;

/**
 * �������� "������������"
 *
 * @property integer                $id                 ID                  �������������
 * @property integer                $xmlId              XML_ID              ������������� ������������ ��� ����� � �������� �����������
 * @property \WS\Tools\ORM\DateTime $modificationDate   TIMESTAMP_X         ���� ���������� ���������
 * @property string                 $login              LOGIN               ��� �����
 * @property string                 $passwordHash       PASSWORD            ��� ������
 * @property string                 $cookiePasswordHash STORED_HASH         ��� ������ �������� � ����� ��������
 * @property string                 $checkword          CHECKWORD           ����������� ������ ����� ������
 * @property boolean                $isActive           ACTIVE              ������� ����������
 * @property string                 $name               NAME ���
 * @property string                 $lastName           LAST_NAME           �������
 * @property string                 $secondName         SECOND_NAME         ��������
 * @property string                 $email              EMAIL
 * @property \WS\Tools\ORM\DateTime $lastLoginDate      LAST_LOGIN          ���� ��������� �����������
 * @property \WS\Tools\ORM\DateTime $lastActivitiDate   LAST_ACTIVITY_DATE  ���� ���������� ���� �� �����
 * @property \WS\Tools\ORM\DateTime $registerDate       DATE_REGISTER       ���� �����������
 * @property string                 $lid                LID                 ������������� ����� �� ��������� ��� �����������
 * @property string                 $adminNotes         ADMIN_NOTES         ������� ��������������
 * @property string                 $externalAuthId     EXTERNAL_AUTH_ID    ��� ��������� ������� �����������
 *
 * @property \WS\Tools\ORM\BitrixEntity\UserGroup[] $groups GROUPS_ID
 *
 * @property string                 $personalProfesion  PERSONAL_PROFESSION ���������
 * @property string                 $personalWWW        PERSONAL_WWW        WWW-��������
 * @property string                 $personalIcq        PERSONAL_ICQ        icq
 * @property string                 $personalGender     PERSONAL_GENDER     ���
 * @property \WS\Tools\ORM\DateTime $personalBirthday   PERSONAL_BIRTHDATE  ���� ��������
 * @property \WS\Tools\ORM\BitrixEntity\File $personalPhoto PERSONAL_PHOTO  ����������
 * @property string                 $personalPhone      PERSONAL_PHONE      �������
 * @property string                 $personalFax        PERSONAL_FAX        ����
 * @property string                 $personalMobile     PERSONAL_MOBILE     ��������� �������
 * @property string                 $personalPager      PERSONAL_PAGER      �������
 * @property string                 $personalStreet     PERSONAL_STREET     �����, ���
 * @property string                 $personalMailBox    PERSONAL_MAILBOX    �������� ����
 * @property string                 $personalCity       PERSONAL_CITY       �����
 * @property string                 $personalState      PERSONAL_STATE      ������� / ����
 * @property string                 $personalZip        PERSONAL_ZIP        ������
 * @property string                 $personalCountry    PERSONAL_COUNTRY    ������
 * @property string                 $personalNotes      PERSONAL_NOTES      �������������� �������
 * 
 * @property string                 $workCompany        WORK_COMPANY        ������������ ��������
 * @property string                 $workDepartment     WORK_DEPARTMENT     ����������� / �����
 * @property string                 $workPosition       WORK_POSITION       ���������
 * @property string                 $workWWW            WORK_WWW            WWW-��������
 * @property string                 $workPhone          WORK_PHONE          �������
 * @property string                 $workFax            WORK_FAX            ����
 * @property string                 $workPager          WORK_PAGER          �������
 * @property string                 $workStreet         WORK_STREET         �����, ���
 * @property string                 $workMailBox        WORK_MAILBOX        �������� ����
 * @property string                 $workCity           WORK_CITY           �����
 * @property string                 $workState          WORK_STATE          ������� / ����
 * @property string                 $workZip            WORK_ZIP            ������
 * @property string                 $workCountry        WORK_COUNTRY        ������
 * @property string                 $workProfile        WORK_PROFILE        ����������� ������������
 * @property \WS\Tools\ORM\BitrixEntity\File $workLogo  WORK_LOGO           �������
 * @property string                 $workNotes          WORK_NOTES          �������������� �������
 * @property string                 $password           PASSWORD
 * @property string                 $passwordConfirm    CONFIRM_PASSWORD
 * @property string                 $skype              UF_SKYPE
 * @property string                 $hideComments       UF_HIDE_COMMENTS    �������� ����������� � ������
 * @property string                 $hideProjects       UF_HIDE_PROJECTS    �������� ������� � ������
 * @property string                 $reverseComments    UF_REVERSE_COMMENTS �������� ����������� � �������� �������
 * @property \WS\Tools\ORM\BitrixEntity\PropEnumElement role UF_ROLE
 *
 * @gateway user
 * @bitrixClass CUser
 *
 * @author ������ ����������� (my.sokolovsky@gmail.com)
 */
class User extends Entity {

    public function getFullName() {
        $name = trim($this->lastName . " " . $this->name);
        return $name ? : $this->email;
    }
}
