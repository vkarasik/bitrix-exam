<?

use Lib\Export\XMLExporter;
use Lib\Export\Orders;

function ExportOrdersAgent()
{
    $orders = new Orders();
    $export = new XMLExporter();
    $export->export($orders->getOrders());
    return __FUNCTION__ . '();';
}
