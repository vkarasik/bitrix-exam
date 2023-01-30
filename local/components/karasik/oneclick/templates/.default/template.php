<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.forms");
CJSCore::Init(array("popup"));

$signedParameters = $this->getComponent()->getSignedParameters();
$initialProducId = $arParams['PRODUCT_ID'];
$is_cart = $arParams['IS_CART'];
?>
<div id="one-click-container">
	<button class="ui-btn ui-btn-lg ui-btn-primary" style="width: 100%">Купить в один клик</button>
</div>

<div id="one-click-popup" class="one-click-popup one-click-popup_hidden">
	<form id="one-click-form" method="POST" onsubmit="addOrder('<?= $signedParameters ?>'); return false;">
		<div class="ui-ctl ui-ctl-textbox">
			<input type="text" class="ui-ctl-element" name="phone" placeholder="Введите ваш телефон">
		</div>
		<input type="hidden" class="product-id" name="product-id" value="<?= $initialProducId ?>">
		<div class="ui-btn-container ui-btn-container-center">
			<?php if ($is_cart) : ?>
				<button class="ui-btn ui-btn-lg  ui-btn-primary" id="one-click-btn" type="submit" data-iscart="<?= $is_cart ?>">Оформить заказ</button>
			<?php else : ?>
				<button class="ui-btn ui-btn-lg  ui-btn-primary" id="one-click-btn" type="submit" data-product-id="<?= $initialProducId ?>">Оформить заказ</button>
			<?php endif ?>
		</div>
	</form>
	<div class="one-click-feedback one-click-feedback_hidden">Заказ принят, с вами свяжется менеджер.</div>
</div>