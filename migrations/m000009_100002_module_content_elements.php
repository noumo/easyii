<?php

use yii\db\Schema;
use yii\easyii\models;
use yii\easyii\modules\content\contentElements\ContentElementBase;

class m000009_100002_module_content_elements extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
	    $this->createTable(ContentElementBase::tableName(), [
		    'element_id' => 'pk',
		    'item_id' => Schema::TYPE_INTEGER,
		    'type' => Schema::TYPE_STRING . ' NOT NULL',
		    'data' => Schema::TYPE_TEXT . ' NOT NULL',
		    'order_num' => Schema::TYPE_INTEGER,
		    'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'",
		    'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
	    ], $this->engine);
	    $this->createIndex('IDX_item_id', ContentElementBase::tableName(), 'item_id');

    }

    public function down()
    {
        $this->dropTable(ContentElementBase::tableName());
    }
}
