<?
namespace Lib\EventHandlers;

class CheckCount
{
	function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		if($arFields["IBLOCK_ID"] == 2 && $arFields["ACTIVE"] == "N"){
			\Bitrix\Main\Loader::includeModule('iblock');
			$tableName = \Bitrix\Iblock\Iblock::wakeUp($arFields["IBLOCK_ID"])->getEntityDataClass();
			$product = $tableName::getByPrimary($arFields["ID"], ["select" => ["SHOW_COUNTER"]])->fetch();
			if($product["SHOW_COUNTER"] > 2 ){
				global $APPLICATION;
				$APPLICATION->throwException("Товар невозможно деактивировать, у него " . $product["SHOW_COUNTER"] . " просмотров.");
				return false;
			}
		}
	}
}
