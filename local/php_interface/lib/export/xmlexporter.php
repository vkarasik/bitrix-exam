<?

namespace Lib\Export;

use Lib\Export\XmlWriter;

class XMLExporter
{
    private $export;

    public function export($arOrders)
    {
        $this->export = new XmlWriter(array(
            'file' => '/upload/orders/export.xml',
            'create_file' => true,
            'charset' => SITE_CHARSET,
        ));
        $this->export->openFile();
        $this->export->writeBeginTag('orders');

        foreach ($arOrders as $id => $order) {
            $this->export->writeBeginTag('order id="' . $id . '"');
            $this->export->writeItem([
                'dateInsert' => $order['DATE_INSERT'],
                'dateUpdate' => $order['DATE_UPDATE'],
                'personTypeId' => $order['PERSON_TYPE_ID'],
                'statusId' => $order['STATUS_ID'],
                'price' => $order['PRICE'],
                'discountValue' => $order['DISCOUNT_VALUE'],
                'userId' => $order['USER_ID'],
                'accountNumber' => $order['ACCOUNT_NUMBER'],
                'payed' => $order['PAYED'],
            ]);

            $this->exportProps($order['PROPS']);
            $this->exportBasket($order['BASKET_ITEMS']);

            $this->export->writeEndTag('order');
        }

        $this->export->writeEndTag('orders');
        $this->export->closeFile();
    }

    private function exportBasket(array $basket)
    {
        $this->export->writeBeginTag('basketItems');
        foreach ($basket as $key => $item) {
            $attr = 'id=' . '"' . $key . '"';
            $this->export->writeBeginTag('basketItem' . ' ' . $attr);
            $this->export->writeItem([
                'productId' => $item['PRODUCT_ID'],
                'name' => $item['NAME'],
                'price' => $item['PRICE'],
                'quantity' => $item['QUANTITY'],
                'discountPrice' => $item['DISCOUNT_PRICE'],
            ]);
            $this->export->writeEndTag('basketItem');
        }
        $this->export->writeEndTag('basketItems');
    }

    private function exportProps($props)
    {
        $this->export->writeBeginTag('properties');

        $this->export->writeBeginTag('userProperties');
        foreach ($props['USER_PROPS'] as $key => $prop) {
            $attr = 'code=' . '"' . $key . '"';
            $this->export->writeBeginTag('property' . ' ' . $attr);
            $this->export->writeValue($prop);
            $this->export->writeEndTag('property');
        }
        $this->export->writeEndTag('userProperties');

        $this->export->writeBeginTag('orderProperties');
        foreach ($props['ORDER_PROPS'] as $key => $prop) {
            $attr = 'code=' . '"' . $key . '"';
            $this->export->writeBeginTag('property' . ' ' . $attr);
            $this->export->writeValue($prop);
            $this->export->writeEndTag('property');
        }
        $this->export->writeEndTag('orderProperties');

        $this->export->writeEndTag('properties');
    }
}
