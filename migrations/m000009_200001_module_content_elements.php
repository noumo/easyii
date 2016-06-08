<?php

use yii\db\Schema;
use yii\easyii\models;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use \yii\easyii\modules\content\models\Item;
use \yii\easyii\modules\content\modules\contentElements\models\ElementOption;

class m000009_200001_module_content_elements extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
		$this->createTable(BaseElement::tableName(), [
		    'element_id' => 'pk',
		    'parent_element_id' => Schema::TYPE_INTEGER,
			'type' => Schema::TYPE_STRING . ' NOT NULL',
			'wrapper' => Schema::TYPE_STRING . ' NOT NULL',
		    'data' => Schema::TYPE_TEXT . ' NOT NULL',
		    'order_num' => Schema::TYPE_INTEGER,
		    'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
		    'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
	    ], $this->engine);
		$this->createIndex('IDX_item_id', BaseElement::tableName(), 'parent_element_id');

		$this->dropColumn(Item::tableName(), 'parent_item_id');
		$this->dropColumn(Item::tableName(), 'content');

		$this->addColumn(Item::tableName(), 'element_id', Schema::TYPE_INTEGER . ' NOT NULL AFTER data');
		$this->createIndex('IDX_element_id', Item::tableName(), 'element_id');
	}

    public function down()
    {
		$this->dropTable(BaseElement::tableName());

		$this->dropColumn(Item::tableName(), 'element_id');
		$this->dropIndex('IDX_element_id', Item::tableName());
    }
}
