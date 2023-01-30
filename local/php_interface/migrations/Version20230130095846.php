<?php

namespace Sprint\Migration;

\Bitrix\Main\Loader::includeModule('sale');

class Version20230130095846 extends Version
{
  protected $description = "Создать группу свойств заказа, свойство заказа";

  protected $moduleVersion = "4.2.4";

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

    $arFieldsIndividual = array(
      "PERSON_TYPE_ID" => 1,
      "NAME" => "Заказ в 1 клик",
      "TYPE" => "Y/N",
      "REQUIED" => "N",
      "DEFAULT_VALUE" => "N",
      "SORT" => 100,
      "CODE" => "SERVICE_DATA",
      "USER_PROPS" => "N",
      "PROPS_GROUP_ID" => $groupIndividualId,
      "UTIL" => "Y",
      "ENTITY_REGISTRY_TYPE" => "ORDER"
    );
    \Bitrix\Sale\Internals\OrderPropsTable::add($arFieldsIndividual);
  }

  public function down()
  {
    //your code ...
  }
}
