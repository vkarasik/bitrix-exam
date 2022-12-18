<?php

namespace Sprint\Migration;


class Version20221218115757 extends Version
{
    protected $description = "Почтовое событие";

    protected $moduleVersion = "4.1.3";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('CALLBACK_FORM', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Отправка сообщения через форму обратной связи',
  'DESCRIPTION' => '#USER_NAME# - Имя отправителя
#USER_SURNAME# - Фамилия отправителя
#COMPANY# - Компания и должность
#DEPARTMENT# - Отдел обращения
#MESSAGE# - Сообщение
#EMAIL_TO# - Email получателя письма
#FILE# - Файл вложения',
  'SORT' => '150',
));
            $helper->Event()->saveEventMessage('CALLBACK_FORM', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => 'no-reply@eshop',
  'EMAIL_TO' => '#EMAIL_TO#',
  'SUBJECT' => 'Форма обратной связи',
  'MESSAGE' => 'Имя: #USER_NAME#
Фамилия: #USER_SURNAME#
Компания: #COMPANY#
Отдел обращения: #DEPARTMENT#
Сообщение: #MESSAGE#
Вложение: #FILE#',
  'BODY_TYPE' => 'text',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => '',
  'EVENT_TYPE' => '[ CALLBACK_FORM ] Отправка сообщения через форму обратной связи',
));
        }

    public function down()
    {
        //your code ...
    }
}
