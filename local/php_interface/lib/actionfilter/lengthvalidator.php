<?php

namespace Lib\ActionFilter;

use Bitrix\Main\Error;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class LengthValidator extends \Bitrix\Main\Engine\ActionFilter\Base

{
    private $fields;
    private $request;
    /**
     * FieldLengthFilter constructor.
     * @param array $fields
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
        $this->request = \Bitrix\Main\Context::getCurrent()->getRequest();
        parent::__construct();
    }

    public function onBeforeAction(Event $event)
    {
        $arErrors = array();
        foreach ($this->fields as $key => $value) {
            if (strlen($this->request[$key]) < $value) {
                $arErrors[$key] = 'Строка должна быть не менее ' . $value . ' символов!';
            }
        }
        if (!empty($arErrors)) {
            $this->addError(new Error($arErrors));
            return new EventResult(EventResult::ERROR, null, null, $this);
        }

        return null;
    }
}
