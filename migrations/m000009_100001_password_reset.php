<?php
use yii\easyii\helpers\Data;
use yii\easyii\helpers\MigrationHelper;
use yii\easyii\models;
use yii\easyii\models\Setting;
use yii\easyii\modules\entity;
use yii\easyii\modules\catalog;
use yii\easyii\modules\shopcart;
use yii\easyii\modules\file;
use yii\easyii\modules\article;
use yii\easyii\modules\carousel\models\Carousel;
use yii\easyii\modules\gallery;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\entity\EntityModule;

class m000009_100001_password_reset extends \yii\db\Migration
{
    // Which version?
    const VERSION = '0.91a';

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        $this->createTable(models\ResetPassword::tableName(), [
            'reset_id' => $this->primaryKey(),
            'email' => $this->string(128) . ' NOT NULL',
            'password' => $this->string(128) . ' NOT NULL',
            'token' => $this->string(128),
            'ip' => $this->string(16),
            'user_agent' => $this->string(1024),
            'time' => $this->integer()->defaultValue(0),
        ]);

        $this->addColumn(models\Admin::tableName(), 'email', $this->string(128) . ' AFTER password');

        $this->insert(Setting::tableName(), [
            'name' => 'root_email',
            'value' => Setting::get('admin_email'),
            'title' => Yii::t('easyii', 'Root email'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        //UPDATE VERSION
        $this->update(models\Setting::tableName(), ['value' => self::VERSION], ['name' => 'easyii_version']);
    }

    public function down()
    {
        //UPDATE VERSION
        $this->update(models\Setting::tableName(), ['value' => 0.91], ['name' => 'easyii_version']);
    }
}
