<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

\Bitrix\Main\UI\Extension::load("ui.forms");
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.alerts");

$signedParameters = $this->getComponent()->getSignedParameters();
?>
<div class="callback-form">
	<h2>Форма обратной связи</h2>
	<form onsubmit="sendCallback('<?=$signedParameters?>'); return false;">
		<div class="name">
			<div class="ui-ctl ui-ctl-textbox">
				<input type="text" name="name" class="ui-ctl-element" placeholder="<?=GetMessage("MFT_NAME")?>">
			</div>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<div class="surname">
			<div class="ui-ctl ui-ctl-textbox">
				<input type="text" name="surname" class="ui-ctl-element" placeholder="<?=GetMessage("MFT_SURNAME")?>">
			</div>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<div class="company">
			<div class="ui-ctl ui-ctl-textbox">
				<input type="text" name="company" class="ui-ctl-element" placeholder="<?=GetMessage("MFT_COMPANY")?>">
			</div>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<div class="department">
			<div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
			<div class="ui-ctl-after ui-ctl-icon-angle"></div>
				<select name="department" class="ui-ctl-element">
					<option selected disabled value=""><?=GetMessage("MFT_DEP")?></option>
					<option value="<?=GetMessage("MFT_DEP_SALE")?>"><?=GetMessage("MFT_DEP_SALE")?></option>
					<option value="<?=GetMessage("MFT_DEP_SUPPORT")?>"><?=GetMessage("MFT_DEP_SUPPORT")?></option>
					<option value="<?=GetMessage("MFT_DEP_LEARN")?>"><?=GetMessage("MFT_DEP_LEARN")?></option>
					<option value="<?=GetMessage("MFT_DEP_SUPPLY")?>"><?=GetMessage("MFT_DEP_SUPPLY")?></option>
				</select>
			</div>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<div class="message">
			<div class="ui-ctl ui-ctl-textarea ui-ctl-no-resize">
				<textarea name="message" class="ui-ctl-element" placeholder="<?=GetMessage("MFT_MESSAGE")?>"></textarea>
			</div>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<div class="picture">
			<label class="ui-ctl ui-ctl-file-drop">
				<div class="ui-ctl-label-text">
					<span>Загрузить картинку</span>
					<small>Перетащить с помощью drag'n'drop</small>
				</div>
				<input type="file" name="picture" class="ui-ctl-element" accept=".png,.jpeg,.jpg,.webp">
			</label>
			<div class="ui-alert ui-alert-danger ui-alert-hidden">
				<span class="ui-alert-message"></span>
			</div>
		</div>
		<button class="ui-btn ui-btn-primary" type="submit"><?=GetMessage("MFT_SUBMIT")?></button>
		<div class="success-msg"></div>
	</form>
</div>
