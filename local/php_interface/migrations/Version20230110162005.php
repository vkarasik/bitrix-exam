<?php

namespace Sprint\Migration;


class Version20230110162005 extends Version
{
    protected $description = "Агент экспорта заказов";

    protected $moduleVersion = "4.1.3";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Agent()->saveAgent(array (
  'MODULE_ID' => '',
  'USER_ID' => NULL,
  'SORT' => '100',
  'NAME' => 'ExportOrdersAgent();',
  'ACTIVE' => 'Y',
  'NEXT_EXEC' => '11.01.2023 16:20:00',
  'AGENT_INTERVAL' => '86400',
  'IS_PERIOD' => 'N',
  'RETRY_COUNT' => '0',
));
    }

    public function down()
    {
        //your code ...
    }
}
