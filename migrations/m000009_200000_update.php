<?php
use yii\easyii\helpers\Data;
use yii\easyii\helpers\MigrationHelper;
use yii\easyii\models;
use yii\easyii\models\Setting;
use yii\easyii\modules\entity;
use yii\easyii\modules\catalog;
use yii\easyii\modules\page\models\Page;
use yii\easyii\modules\shopcart;
use yii\easyii\modules\file;
use yii\easyii\modules\article;
use yii\easyii\modules\carousel\models\Carousel;
use yii\easyii\modules\gallery;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\entity\EntityModule;

class m000009_200000_update extends \yii\db\Migration
{
    const VERSION = 0.92;

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        $this->addColumn(Page::tableName(), 'fields', $this->text());
        $this->addColumn(Page::tableName(), 'data', $this->text());
        $this->addColumn(Page::tableName(), 'tree', $this->integer()->defaultValue(0));
        $this->addColumn(Page::tableName(), 'lft', $this->integer()->defaultValue(0));
        $this->addColumn(Page::tableName(), 'rgt', $this->integer()->defaultValue(0));
        $this->addColumn(Page::tableName(), 'depth', $this->integer()->defaultValue(0));
        $this->addColumn(Page::tableName(), 'order_num', $this->integer()->defaultValue(0));
        $this->addColumn(Page::tableName(), 'status', $this->boolean()->defaultValue(1));

        $i = 1;
        foreach(Page::find()->all() as $page) {
            $page->tree = $i;
            $page->lft = 1;
            $page->rgt = 2;
            $page->order_num = $i++;
            $page->update(false, ['tree', 'lft', 'rgt', 'order_num']);
        }

        MigrationHelper::appendModuleSettings('page', [
            'slugImmutable' => false,
        ]);
        MigrationHelper::appendModuleSettings('page', [
            'defaultFields' => '[]',
        ]);
    }

    public function down()
    {
        $this->dropColumn(Page::tableName(), 'fields');
        $this->dropColumn(Page::tableName(), 'data');
        $this->dropColumn(Page::tableName(), 'tree');
        $this->dropColumn(Page::tableName(), 'lft');
        $this->dropColumn(Page::tableName(), 'rgt');
        $this->dropColumn(Page::tableName(), 'depth');
        $this->dropColumn(Page::tableName(), 'order_num');
        $this->dropColumn(Page::tableName(), 'status');
    }
}
