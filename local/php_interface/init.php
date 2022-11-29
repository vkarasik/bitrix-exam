<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/kint.phar");

function dump($var){
	\Bitrix\Main\Diag\Debug::dump($var);
}
