<?php

use yii\db\Schema;
use yii\easyii\models;

use \yii\easyii\modules\content\models\Item;
use \yii\easyii\modules\content\models\ItemData;
use yii\easyii\modules\content\models\Layout;

class m000009_100001_install_content_module extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        $this->createTable(Layout::tableName(), [
            'category_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image_file' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'fields' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', Layout::tableName(), 'slug', true);

        $this->createTable(Item::tableName(), [
            'item_id' => 'pk',
	        'category_id' => Schema::TYPE_INTEGER,
	        'parent_item_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'header' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'nav' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '1'",
            'content' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'image_file' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
	        'tree' => Schema::TYPE_INTEGER,
	        'lft' => Schema::TYPE_INTEGER,
	        'rgt' => Schema::TYPE_INTEGER,
	        'depth' => Schema::TYPE_INTEGER,
	        'order_num' => Schema::TYPE_INTEGER,
	        'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', Item::tableName(), 'slug', true);

    }

    public function down()
    {
	    $this->dropTable(ItemData::tableName());
	    $this->dropTable(Item::tableName());
        $this->dropTable(Layout::tableName());
    }
}
