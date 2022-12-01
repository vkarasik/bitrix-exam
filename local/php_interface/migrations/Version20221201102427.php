<?php

namespace Sprint\Migration;

class Version20221201102427 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.3";

    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Option()->saveOption(array(
            'MODULE_ID' => 'fileman',
            'NAME' => 'propstypes',
            'VALUE' => 'a:5:{s:11:\"description\";s:33:\"Описание страницы\";s:8:\"keywords\";s:27:\"Ключевые слова\";s:5:\"title\";s:44:\"Заголовок окна браузера\";s:14:\"keywords_inner\";s:35:\"Продвигаемые слова\";s:11:\"specialdate\";s:31:\"Специальная дата\";}',
        ));
    }

    public function down()
    {
        //your code ...
    }
}
