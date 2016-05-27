<?php

use yii\db\Schema;
use yii\easyii\models;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use \yii\easyii\modules\content\models\Item;
use \yii\easyii\modules\content\modules\contentElements\models\ElementOption;

class m000009_200002_module_content_options extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
		$this->createTable(ElementOption::tableName(), [
			'option_id' => 'pk',
			'element_id' => Schema::TYPE_INTEGER,
			'type' => $this->string(50),
			'value' => Schema::TYPE_TEXT . ' DEFAULT NULL',
			'timestamp' => $this->timestamp() .  " DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
		], $this->engine);
		$this->createIndex('IDX_option_element_id', ElementOption::tableName(), 'element_id');
		$this->createIndex('UNQ_option_element', ElementOption::tableName(), ['element_id', 'type'], true);

		$types = [ElementOption::TYPE_ID, ElementOption::TYPE_CLASS, ElementOption::TYPE_STYLE];
		foreach ($types as $type) {
			$this->execute("
				INSERT INTO easyii_content_element_option (element_id, type)
				SELECT element_id, '$type' FROM easyii_content_element
			");
		}

	}

    public function down()
    {
		$this->dropTable(ElementOption::tableName());
    }
}
