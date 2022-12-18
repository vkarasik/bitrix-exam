<?php

use Lib\ActionFilter\LengthValidator;

class CustomCallback extends \CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{
    public function configureActions() {
        return [
            'ajaxRequest' => [
                'prefilters' => [
                    new LengthValidator([
                        'name' => 2,
                        'surname' => 2,
                        'message' => 10,
                        'department' => 1,
                    ]),
                ],
            ],
        ];
    }

    public function onPrepareComponentParams($arParams) {
        $arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
        if ($arParams["EVENT_NAME"] == '') {
            $arParams["EVENT_NAME"] = "CALLBACK_FORM";
        }
        return $arParams;
    }

    protected function listKeysSignedParameters() {
        return [
            'EMAIL_TO',
            'EVENT_MESSAGE_ID',
            'EVENT_NAME',
            'OK_TEXT',
            'IBLOCK_ID',
        ];
    }

    public function executeComponent() {
        $this->includeComponentTemplate();
    }

    public function ajaxRequestAction() {
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $file = \Bitrix\Main\Context::getCurrent()->getRequest()->getFile('picture');
        $arFields = array(
            'NAME' => htmlspecialcharsEx($request['name']),
            'SURNAME' => htmlspecialcharsEx($request['surname']),
            'COMPANY' => htmlspecialcharsEx($request['company']),
            'DEPARTMENT' => htmlspecialcharsEx($request['department']),
            'MESSAGE' => htmlspecialcharsEx($request['message']),
            'FILE_PATH' => $this->saveFile($file),
        );
        $iblockId = intval($this->arParams['IBLOCK_ID']);
        $ibElement = $this->saveRequest($arFields, $iblockId);
        if (is_numeric($ibElement)) {

            $this->sendMail($arFields);

            return new \Bitrix\Main\Engine\Response\Component(
                "karasik:response",
                ".default",
                array(
                    'RESPONSE_MSG' => $this->arParams['OK_TEXT'],
                )
            );
        } else {
            return 'Error: ' . $ibElement;
        }
    }

    /**
     * @param array $arFields
     * @param int  $iblockId
     */
    private function saveRequest($arFields, $iblockId) {
        \Bitrix\Main\Loader::includeModule('iblock');
        $el = new CIBlockElement();
        $props = array();
        $props['NAME'] = $arFields['NAME'];
        $props['SURNAME'] = $arFields['SURNAME'];
        $props['COMPANY'] = $arFields['COMPANY'];
        $props['DEPARTMENT'] = $arFields['DEPARTMENT'];

        $arLoadMessageArray = array(
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_VALUES' => $props,
            'NAME' => $arFields['NAME'] . ' ' . $arFields['SURNAME'],
            'ACTIVE' => 'Y',
            'DETAIL_TEXT' => $arFields['MESSAGE'],
            'DETAIL_PICTURE' => CFile::MakeFileArray($arFields['FILE_PATH']),
        );

        if ($elementID = $el->Add($arLoadMessageArray)) {
            return $elementID;
        } else {
            return $el->LAST_ERROR;
        }
    }

    /**
     * @param array $file
     */
    private function saveFile($file) {
        if (move_uploaded_file($file['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . '/upload/tmp/' . $file['name'])) {
            $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . '/upload/tmp/' . $file['name']);
            $arFile['MODULE_ID'] = 'iblock';
            $fileID = CFile::SaveFile($arFile, 'form');
            unlink($_SERVER["DOCUMENT_ROOT"] . '/upload/tmp/' . $file['name']);
            return CFile::GetPath($fileID);
        }
        return;
    }

    /**
     * @param array $arFields
     */
    private function sendMail($arFields) {
        $cFields = array(
            "EMAIL_TO" => $this->arParams['EMAIL_TO'],
            "USER_NAME" => $arFields['NAME'],
            "USER_SURNAME" => $arFields['SURNAME'],
            "COMPANY" => $arFields['COMPANY'],
            "DEPARTMENT" => $arFields['DEPARTMENT'],
            "MESSAGE" => $arFields['MESSAGE'],
        );

        $eventData = array(
            "EVENT_NAME" => $this->arParams['EVENT_NAME'],
            "LID" => SITE_ID,
            "C_FIELDS" => $cFields,
            "FILE" => array($arFields['FILE_PATH']),
        );

        if (!empty($this->arParams["EVENT_MESSAGE_ID"])) {
            foreach ($this->arParams["EVENT_MESSAGE_ID"] as $v) {
                if (intval($v) > 0) {
                    $eventData['MESSAGE_ID'] = intval($v);
                    \Bitrix\Main\Mail\Event::send($eventData);
                }
            }
        } else {
            \Bitrix\Main\Mail\Event::send($eventData);
        }
    }
}
