<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */

use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock;

Loader::includeModule('catalog');
Loader::IncludeModule('highloadblock');

$iBlockId = $arParams['PARAMS']['IBLOCK_ID'];

/**
 * Название текущего раздела каталога
 */
function getSectionName($iBlockId, $sectionCode)
{
	$rsSections = CIBlockSection::GetList(
		array(),
		array(
			'IBLOCK_ID' => $iBlockId,
			'=CODE' => $sectionCode
		)
	);
	if ($arSection = $rsSections->Fetch()) {
		$sectionName =  $arSection['NAME'];
	}
	return $sectionName;
}

/**
 * Свойство по ID
 */
function getProperty($propertyId)
{
	$res = CIBlockProperty::GetByID($propertyId);
	if ($propArr = $res->Fetch()) {
		return $propArr;
	}
}

/**
 * Значение списочного свойства по ID
 */
function getPropertyValueById($propertyId, $propertyItemId, $iBlockId = null)
{
	$enumList = CIBlockProperty::GetPropertyEnum(
		$propertyId,
		array(),
		array(
			"ID" => $propertyItemId,
		)
	);
	if ($arEnumList = $enumList->Fetch()) {
		return $arEnumList['VALUE'];
	}
}

/**
 * Значение свойства из HL блока
 */
function getPropertyValueHighLoad($hlTableName, $propXmlId)
{
	$hlblock = Highloadblock\HighloadBlockTable::getRow([
		'filter' => [
			'=TABLE_NAME' => $hlTableName
		],
	]);

	$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
	$entity_data_class = $entity->getDataClass();
	$res = $entity_data_class::getList(
		array(
			'filter' => array(
				'UF_XML_ID' => $propXmlId,
			)
		)
	);
	if ($item = $res->fetch()) {
		//  print_r($item['UF_NAME']);
		return $item['UF_NAME'];
	}
}

$sectionName = getSectionName($iBlockId, $arParams['SECTION_CODE']);
$title = $sectionName;

// Массив с выбранными фильтрами
$arfilter = $GLOBALS['arrFilter'];
if (isset($arfilter['OFFERS'])) {
	$arfilter = array_merge($arfilter, $arfilter['OFFERS']);
	unset($arfilter['OFFERS']);
}

foreach ($arfilter as $key => $arProps) {
	$propId = preg_replace('/^(=PROPERTY_)(.+)$/', '${2}', $key);
	$property = getProperty($propId);
	$propName = $property['NAME'];
	$propType = $property['PROPERTY_TYPE'];
	$hlTableName =  $property['USER_TYPE_SETTINGS']['TABLE_NAME'] ?? false;
	$title .= '.' . ' ' . $propName;
	// I'm sorry! :)
	foreach ($arProps as $prop) {
		$delimiter = next($arProps) ? ',' : '';
		// Свойство типа справочник
		if ($hlTableName) {
			$propValue = getPropertyValueHighLoad($hlTableName, $prop);
		}
		// Свойство типа список
		elseif ($propType == 'L') {
			$propValue = getPropertyValueById($propId, $prop);
		}
		// Свойство строка
		else {
			$propValue = $prop;
		}
		$title .= ' ' . $propValue . $delimiter;
	}
}

$APPLICATION->setTitle($title);
$APPLICATION->setPageProperty('title', $title);

// $this->includeComponentTemplate();
