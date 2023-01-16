<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/kint.phar");

function dump($var)
{
	\Bitrix\Main\Diag\Debug::dump($var);
}

use Bitrix\Main\Loader;

Loader::registerNamespace(
	"Lib",
	Loader::getDocumentRoot() . "/local/php_interface/lib"
);

include('include/agents.php');
