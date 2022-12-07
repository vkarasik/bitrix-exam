<?php

namespace Lib\Labels;

class Labels
{
    const HLB_ID = 5;
    public static function getLabels()
    {
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(self::HLB_ID);
        $entityDataClass = $entity->getDataClass();
        $rsData = $entityDataClass::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
        ));
        $HLBProps = array();
        while ($arData = $rsData->fetch()) {
            $HLBProps[$arData["UF_XML_ID"]] = $arData;
        }
        return $HLBProps;
    }
}
