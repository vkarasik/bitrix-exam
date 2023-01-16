<?

/**
 * Orders
 */

namespace Lib\Export;

use Bitrix\Sale;

class Orders
{
    private $arOrders = array();

    public function __construct()
    {
        $dbRes = \Bitrix\Sale\Order::getList([
            'select' => [
                'ID',
                'DATE_INSERT',
                'DATE_UPDATE',
                'PERSON_TYPE_ID',
                'STATUS_ID',
                'PRICE',
                'DISCOUNT_VALUE',
                'USER_ID',
                'ACCOUNT_NUMBER',
                'PAYED',
            ],
            'filter' => [
                '=PROPERTY.CODE' => 'EXPORT',
                '=PROPERTY.VALUE' => 'N',
            ],
            'order' => ['ID' => 'DESC'],
        ]);

        while ($order = $dbRes->fetch()) {
            $this->arOrders[$order['ID']] = $order;
            $this->arOrders[$order['ID']]['PROPS']['USER_PROPS'] = $this->getProps($order['ID'], 1);
            $this->arOrders[$order['ID']]['PROPS']['ORDER_PROPS'] = $this->getProps($order['ID'], 2);
            $this->arOrders[$order['ID']]['BASKET_ITEMS'] = $this->getBasketItems($order['ID']);
            $this->markAsExported($order['ID'], $order['PERSON_TYPE_ID']);
        }
    }

    private function getBasketItems(int $orderId)
    {
        $order = Sale\Order::load($orderId);
        $orderBasket = $order->getBasket()->getBasketItems();
        $basketItems = array();
        foreach ($orderBasket as $item) {
            $basketItems[$item->getId()] = array(
                'PRODUCT_ID' => $item->getProductId(),
                'NAME' => $item->getField('NAME'),
                'PRICE' => $item->getFinalPrice(),
                'BASE_PRICE' => $item->getBasePrice(),
                'QUANTITY' => $item->getQuantity(),
                'DISCOUNT_PRICE' => $item->getDiscountPrice(),
            );
        }
        return $basketItems;
    }

    private function getProps(int $orderId, int $groupId)
    {
        $order = Sale\Order::load($orderId);
        $propertyCollection = $order->getPropertyCollection();
        $props = $propertyCollection->getPropertiesByGroupId($groupId);
        $arOrderProps = array();
        foreach ($props as $prop) {
            $prop = $prop->toArray();
            $arOrderProps[$prop['CODE']] = $prop['VALUE'];
        }
        return $arOrderProps;
    }

    private function markAsExported(int $orderId, string $personTypeId)
    {
        $order = Sale\Order::load($orderId);
        $propertyCollection = $order->getPropertyCollection();
        $groupId = $this->getPropertiesGroupId("SERVICE_DATA", $personTypeId);
        $props = $propertyCollection->getPropertiesByGroupId($groupId);
        $props[0]->setValue("Y");
        $order->save();
    }

    private function getPropertiesGroupId(string $code, string $personTypeId)
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

    public function getOrders()
    {
        return $this->arOrders;
    }
}
