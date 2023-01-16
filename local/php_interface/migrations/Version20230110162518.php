<?php

namespace Sprint\Migration;

\Bitrix\Main\Loader::includeModule('sale');

class Version20230110162518 extends Version
{
  protected $description = "Создать группы свойств заказа, свойства заказа, и значения по умолчанию в заказе";

  protected $moduleVersion = "4.1.3";

  public function up()
  {
    // Создать группу свойств для физлиц
    $arFieldsGroupIndividual = array(
      "PERSON_TYPE_ID" => 1,
      "NAME" => "Служебные данные",
      "SORT" => 500,
      "CODE" => "SERVICE_DATA"
    );
    $groupIndividualId = \Bitrix\Sale\Internals\OrderPropsGroupTable::add($arFieldsGroupIndividual)->getId();

    // Создать группу свойств для юрлиц
    $arFieldsGroupLegal = array(
      "PERSON_TYPE_ID" => 2,
      "NAME" => "Служебные данные",
      "SORT" => 500,
      "CODE" => "SERVICE_DATA"
    );
    $groupLegalId = \Bitrix\Sale\Internals\OrderPropsGroupTable::add($arFieldsGroupLegal)->getId();


    // Создать свойство заказа для физлица
    $arFieldsIndividual = array(
      "PERSON_TYPE_ID" => 1,
      "NAME" => "Заказ экспортирован",
      "TYPE" => "Y/N",
      "REQUIED" => "N",
      "DEFAULT_VALUE" => "N",
      "SORT" => 100,
      "CODE" => "EXPORT",
      "USER_PROPS" => "N",
      "PROPS_GROUP_ID" => $groupIndividualId,
      "UTIL" => "Y",
      "ENTITY_REGISTRY_TYPE" => "ORDER"
    );
    \Bitrix\Sale\Internals\OrderPropsTable::add($arFieldsIndividual);

    // Свойство заказа для юрлица
    $arFieldsLegal = array(
      "PERSON_TYPE_ID" => 2,
      "NAME" => "Заказ экспортирован",
      "TYPE" => "Y/N",
      "REQUIED" => "N",
      "DEFAULT_VALUE" => "N",
      "SORT" => 100,
      "CODE" => "EXPORT",
      "USER_PROPS" => "N",
      "PROPS_GROUP_ID" => $groupLegalId,
      "UTIL" => "Y",
      "ENTITY_REGISTRY_TYPE" => "ORDER"
    );
    \Bitrix\Sale\Internals\OrderPropsTable::add($arFieldsLegal);

    // Задать заказам значение свойства по умолчанию
    $dbRes = \Bitrix\Sale\Order::getList([
      'select' => [
        'ID',
        'PERSON_TYPE_ID'
      ],
      'order' => ['ID' => 'DESC'],
    ]);

    while ($order = $dbRes->fetch()) {
      $personTypeId =  $order['PERSON_TYPE_ID'];
      $order = \Bitrix\Sale\Order::load($order['ID']);
      $propertyCollection = $order->getPropertyCollection();
      $groupId = $this->getPropertiesGroupId("SERVICE_DATA", $personTypeId);
      $props = $propertyCollection->getPropertiesByGroupId($groupId);
      $props[0]->setValue("N");
      $order->save();
    }
  }

  public function getPropertiesGroupId(string $code, $personTypeId)
  {
    $result = \Bitrix\Sale\Internals\OrderPropsGroupTable::getList([
      'select' => array('ID'),
      'filter' => array(
        'CODE' => $code,
        "PERSON_TYPE_ID" => $personTypeId,
      )
    ]);
    $arr = $result->fetch();
    return $arr["ID"];
  }

  public function down()
  {
    //your code ...
  }
}
