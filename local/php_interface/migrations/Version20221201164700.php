<?php

namespace Sprint\Migration;


class Version20221201164700 extends Version
{
    protected $description = "Установить поле API_CODE";

    protected $moduleVersion = "4.1.3";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->saveIblock(array (
  'IBLOCK_TYPE_ID' => 'catalog',
  'LID' => 
  array (
    0 => 's1',
  ),
  'CODE' => 'clothes',
  'API_CODE' => 'clothes',
  'REST_ON' => 'N',
  'NAME' => 'Одежда',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'LIST_PAGE_URL' => '#SITE_DIR#/catalog/',
  'DETAIL_PAGE_URL' => '#SITE_DIR#/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
  'SECTION_PAGE_URL' => '#SITE_DIR#/catalog/#SECTION_CODE#/',
  'CANONICAL_PAGE_URL' => '',
  'PICTURE' => '36',
  'DESCRIPTION' => 'Одежда нашей основной фабрики в Санкт-Петербурге.',
  'DESCRIPTION_TYPE' => 'html',
  'RSS_TTL' => '24',
  'RSS_ACTIVE' => 'Y',
  'RSS_FILE_ACTIVE' => 'N',
  'RSS_FILE_LIMIT' => NULL,
  'RSS_FILE_DAYS' => NULL,
  'RSS_YANDEX_ACTIVE' => 'N',
  'XML_ID' => 'clothes_s1',
  'INDEX_ELEMENT' => 'Y',
  'INDEX_SECTION' => 'Y',
  'WORKFLOW' => 'N',
  'BIZPROC' => 'N',
  'SECTION_CHOOSER' => 'L',
  'LIST_MODE' => 'S',
  'RIGHTS_MODE' => 'S',
  'SECTION_PROPERTY' => 'Y',
  'PROPERTY_INDEX' => 'Y',
  'VERSION' => '1',
  'LAST_CONV_ELEMENT' => '0',
  'SOCNET_GROUP_ID' => NULL,
  'EDIT_FILE_BEFORE' => '',
  'EDIT_FILE_AFTER' => '',
  'SECTIONS_NAME' => 'Разделы',
  'SECTION_NAME' => 'Раздел',
  'ELEMENTS_NAME' => 'Товары',
  'ELEMENT_NAME' => 'Товар',
  'EXTERNAL_ID' => 'clothes_s1',
  'LANG_DIR' => '/',
  'SERVER_NAME' => 'eshop',
  'IPROPERTY_TEMPLATES' => 
  array (
    'ELEMENT_META_TITLE' => 'Каталог {=this.Name} от магазина Одежда+',
    'ELEMENT_META_KEYWORDS' => '{=this.Name}, купить {=this.Name}, приобрести {=this.Name}, {=this.Name} в различных цветах, {=this.Name} от дистрибьютора',
    'ELEMENT_META_DESCRIPTION' => 'В магазине Одежда+ собран огромный каталог, где не последняя роль отведена разделу {=this.Name}, представленный официальным дистрибьютором в России',
    'ELEMENT_DETAIL_PICTURE_FILE_TITLE' => 'картинка {=this.Name} магазин Одежда+ являющийся официальным дистрибьютором в России ',
    'ELEMENT_DETAIL_PICTURE_FILE_NAME' => '{=lower this.Name}/lt-',
    'ELEMENT_DETAIL_PICTURE_FILE_ALT' => 'картинка {=this.Name} от магазина Одежда+',
    'ELEMENT_PREVIEW_PICTURE_FILE_TITLE' => 'картинка {=this.Name} магазин Одежда+ являющийся официальным дистрибьютором в России ',
    'SECTION_META_DESCRIPTION' => 'В магазине Одежда+ собран огромный каталог, где не последняя роль отведена разделу {=this.Name}, представленный официальным дистрибьютором в России',
    'ELEMENT_PREVIEW_PICTURE_FILE_ALT' => 'картинка {=this.Name} от магазина Одежда+',
    'SECTION_META_KEYWORDS' => '{=this.Name}, купить {=this.Name}, приобрести {=this.Name}, {=this.Name} в различных цветах, {=this.Name} от дистрибьютора',
    'SECTION_META_TITLE' => 'Каталог {=this.Name} от магазина Одежда+',
    'ELEMENT_PREVIEW_PICTURE_FILE_NAME' => '{=lower this.Name}/lt-',
  ),
  'ELEMENT_ADD' => 'Добавить товар',
  'ELEMENT_EDIT' => 'Изменить товар',
  'ELEMENT_DELETE' => 'Удалить товар',
  'SECTION_ADD' => 'Добавить раздел',
  'SECTION_EDIT' => 'Изменить раздел',
  'SECTION_DELETE' => 'Удалить раздел',
));

    }

    public function down()
    {
        //your code ...
    }
}
