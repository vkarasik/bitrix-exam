<?php

use
    Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale\Fuser;

class OneClickComponent extends \CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{
    public function configureActions()
    {
        return [
            'ajaxRequest' => [
                'prefilters' => [],
            ],
        ];
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    protected function listKeysSignedParameters()
    {
        return [
            'PRODUCT_ID',
            'IS_CART',
        ];
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    public function ajaxRequestAction()
    {
        \Bitrix\Main\Loader::includeModule('sale');

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $arFields = array(
            'PHONE' => htmlspecialcharsEx($request['phone']),
            'IS_CART' => $this->arParams['IS_CART'],
        );
        $order = $this->createOrder($arFields);

        if ($order) {
            return "Заказ #$order принят";
        } else {
            return 'Error: ' . $order;
        }
    }

    /**
     * @param array $arFields
     * @return $oderId
     */
    private function createOrder(array $arFields)
    {
        \Bitrix\Main\Loader::includeModule('sale');
        \Bitrix\Main\Loader::includeModule("catalog");

        $siteId = Context::getCurrent()->getSite();
        $currencyCode = CurrencyManager::getBaseCurrency();

        $basket = $this->getBasket($siteId, $arFields);

        $order = Order::create($siteId, Fuser::getId());
        $order->setBasket($basket);
        $order->setPersonTypeId(1);
        $order->setField('CURRENCY', $currencyCode);

        // Создаём отгрузку и устанавливаем способ доставки - "Самовывоз" (ID 3)
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(
            Bitrix\Sale\Delivery\Services\Manager::getObjectById(3)
        );
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        // Создаём оплату со способом - "Наличными курьеру" (ID 2)
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem(
            Bitrix\Sale\PaySystem\Manager::getObjectById(2)
        );
        $payment->setField("SUM", $order->getPrice());
        $payment->setField("CURRENCY", $order->getCurrency());

        // Устанавливаем свойства заказа
        $propertyCollection = $order->getPropertyCollection();
        $phoneProp = $propertyCollection->getPhone();
        $phoneProp->setValue($arFields['PHONE']);
        // Cвойство заказа «Заказ в 1 клик»
        $oneClickProp = $propertyCollection->getItemByOrderPropertyCode("SERVICE_DATA");
        $oneClickProp->setValue('Y');

        $order->doFinalAction(true);
        $result = $order->save();
        if (!$result->isSuccess()) {
            return $result->getErrors();
        }
        return $order->getId();
    }

    /**
     * @param $siteId
     * @param array $arFields
     * @return $basket
     */
    private function getBasket($siteId, array $arFields)
    {
        \Bitrix\Main\Loader::includeModule('sale');

        if ($arFields['IS_CART']) {
            // Получить существующую корзину
            return Basket::loadItemsForFUser(Fuser::getId(), $siteId);
        } else {
            // Создать новую корзину
            $request = \Bitrix\Main\Context::getCurrent()->getRequest();
            $productId = $request['product-id'];
            $quantity = $request['quantity'];
            $basket = Basket::create($siteId);
            $item = $basket->createItem('catalog', $productId);
            $item->setFields(array(
                'QUANTITY' => $quantity,
                'LID' => $siteId,
                'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
            ));
        }
        return $basket;
    }
}
