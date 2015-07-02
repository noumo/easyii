<?php
use yii\db\Schema;
use yii\easyii\models;

use yii\easyii\modules\catalog;
use yii\easyii\modules\shopcart;
use yii\easyii\modules\article;
use yii\easyii\modules\carousel\models\Carousel;
use yii\easyii\modules\faq\models\Faq;
use yii\easyii\modules\feedback\models\Feedback;
use yii\easyii\modules\file\models\File;
use yii\easyii\modules\gallery;
use yii\easyii\modules\guestbook\models\Guestbook;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\page\models\Page;
use yii\easyii\modules\subscribe\models\Subscriber;
use yii\easyii\modules\subscribe\models\History;
use yii\easyii\modules\text\models\Text;

class m000000_000000_install extends \yii\db\Migration
{
    const VERSION = 0.9;

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //ADMINS
        $this->createTable(models\Admin::tableName(), [
            'admin_id' => 'pk',
            'username' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password' => Schema::TYPE_STRING . '(64) NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(128) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
        ], $this->engine);
        $this->createIndex('access_token', models\Admin::tableName(), 'access_token', true);

        //LOGINFORM
        $this->createTable(models\LoginForm::tableName(), [
            'log_id' => 'pk',
            'username' => Schema::TYPE_STRING . '(128) NOT NULL',
            'password' => Schema::TYPE_STRING . '(128) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'user_agent' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'time' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'success' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $this->engine);

        //MODULES
        $this->createTable(models\Module::tableName(), [
            'module_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'icon' => Schema::TYPE_STRING . '(32) NOT NULL',
            'settings' => Schema::TYPE_TEXT . ' NOT NULL',
            'notice' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $this->engine);
        $this->createIndex('name', models\Module::tableName(), 'name', true);

        //PHOTOS
        $this->createTable(models\Photo::tableName(), [
            'photo_id' => 'pk',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'image' => Schema::TYPE_STRING . '(128) NOT NULL',
            'description' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'order_num' => Schema::TYPE_INTEGER . " NOT NULL",
        ], $this->engine);
        $this->createIndex('model_item', models\Photo::tableName(), ['class', 'item_id']);

        //SEOTEXT
        $this->createTable(models\SeoText::tableName(), [
            'seotext_id' => 'pk',
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'h1' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'keywords' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'description' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
        ], $this->engine);
        $this->createIndex('model_item', models\SeoText::tableName(), ['class', 'item_id'], true);

        //SETTINGS
        $this->createTable(models\Setting::tableName(), [
            'setting_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'visibility' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
        ], $this->engine);
        $this->createIndex('name', models\Setting::tableName(), 'name', true);

        //CAROUSEL MODULE
        $this->createTable(Carousel::tableName(), [
            'carousel_id' => 'pk',
            'image' => Schema::TYPE_STRING . '(128) NOT NULL',
            'link' => Schema::TYPE_STRING . '(255) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);

        //CATALOG MODULE
        $this->createTable(catalog\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'fields' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', catalog\models\Category::tableName(), 'slug', true);

        $this->createTable(catalog\models\Item::tableName(), [
            'item_id' => 'pk',
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'description' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'available' => Schema::TYPE_INTEGER . " DEFAULT '1'",
            'price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'discount' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', catalog\models\Item::tableName(), 'slug', true);

        $this->createTable(catalog\models\ItemData::tableName(), [
            'data_id' => 'pk',
            'item_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
        ], $this->engine);
        $this->createIndex('item_id_name', catalog\models\ItemData::tableName(), ['item_id', 'name']);
        $this->createIndex('value', catalog\models\ItemData::tableName(), 'value(300)');

        //SHOPCART MODULE
        $this->createTable(shopcart\models\Order::tableName(), [
            'order_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) NOT NULL',
            'phone' => Schema::TYPE_STRING . '(64) NOT NULL',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'comment' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'remark' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(32) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'new' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $this->engine);

        $this->createTable(shopcart\models\Good::tableName(), [
            'good_id' => 'pk',
            'order_id' => Schema::TYPE_INTEGER,
            'item_id' => Schema::TYPE_INTEGER,
            'count' => Schema::TYPE_INTEGER,
            'options' => Schema::TYPE_STRING . '(255) NOT NULL',
            'price' => Schema::TYPE_FLOAT . " DEFAULT '0'",
            'discount' => Schema::TYPE_INTEGER . " DEFAULT '0'",
        ], $this->engine);

        //FEEDBACK MODULE
        $this->createTable(Feedback::tableName(), [
            'feedback_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'phone' => Schema::TYPE_STRING . '(64) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer_subject' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'answer_text' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $this->engine);

        //FILE MODULE
        $this->createTable(File::tableName(), [
            'file_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'file' => Schema::TYPE_STRING . '(255) NOT NULL',
            'size' => Schema::TYPE_INTEGER .  ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'downloads' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'order_num' => Schema::TYPE_INTEGER,
        ], $this->engine);
        $this->createIndex('slug', File::tableName(), 'slug', true);

        //GALLERY MODULE
        $this->createTable(gallery\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', gallery\models\Category::tableName(), 'slug', true);

        //GUESTBOOK MODULE
        $this->createTable(Guestbook::tableName(), [
            'guestbook_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'title' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'email' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'new' => Schema::TYPE_BOOLEAN . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '0'"
        ], $this->engine);

        //NEWS MODULE
        $this->createTable(News::tableName(), [
            'news_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'short' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'views' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', News::tableName(), 'slug', true);

        //ARTICLE MODULE
        $this->createTable(article\models\Category::tableName(), [
            'category_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER,
            'rgt' => Schema::TYPE_INTEGER,
            'depth' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', article\models\Category::tableName(), 'slug', true);

        $this->createTable(article\models\Item::tableName(), [
            'item_id' => 'pk',
            'category_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'image' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'short' => Schema::TYPE_STRING . '(1024) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'views' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);
        $this->createIndex('slug', article\models\Item::tableName(), 'slug', true);

        //PAGE MODULE
        $this->createTable(Page::tableName(), [
            'page_id' => 'pk',
            'title' => Schema::TYPE_STRING . '(128) NOT NULL',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
        ], $this->engine);
        $this->createIndex('slug', Page::tableName(), 'slug', true);

        //FAQ MODULE
        $this->createTable(Faq::tableName(), [
            'faq_id' => 'pk',
            'question' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer' => Schema::TYPE_TEXT . ' NOT NULL',
            'order_num' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_BOOLEAN . " DEFAULT '1'"
        ], $this->engine);

        //SUBSCRIBE MODULE
        $this->createTable(Subscriber::tableName(), [
            'subscriber_id' => 'pk',
            'email' => Schema::TYPE_STRING . '(128) NOT NULL',
            'ip' => Schema::TYPE_STRING . '(16) NOT NULL',
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'"
        ], $this->engine);
        $this->createIndex('email', Subscriber::tableName(), 'email', true);

        $this->createTable(History::tableName(), [
            'history_id' => 'pk',
            'subject' => Schema::TYPE_STRING . '(128) NOT NULL',
            'body' => Schema::TYPE_TEXT . ' NOT NULL',
            'sent' => Schema::TYPE_INTEGER .  " DEFAULT '0'",
            'time' => Schema::TYPE_INTEGER .  " DEFAULT '0'"
        ], $this->engine);

        //TEXT MODULE
        $this->createTable(Text::tableName(), [
            'text_id' => 'pk',
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'slug' => Schema::TYPE_STRING . '(128) DEFAULT NULL'
        ], $this->engine);
        $this->createIndex('slug', Text::tableName(), 'slug', true);

        //Tags
        $this->createTable(models\Tag::tableName(), [
            'tag_id' => 'pk',
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'frequency' => Schema::TYPE_INTEGER . " DEFAULT '0'"
        ], $this->engine);
        $this->createIndex('name', models\Tag::tableName(), 'name', true);

        $this->createTable(models\TagAssign::tableName(), [
            'class' => Schema::TYPE_STRING . '(128) NOT NULL',
            'item_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'tag_id' => Schema::TYPE_INTEGER . " NOT NULL",
        ], $this->engine);
        $this->createIndex('class', models\TagAssign::tableName(), 'class');
        $this->createIndex('item_tag', models\TagAssign::tableName(), ['item_id', 'tag_id']);

        //INSERT VERSION
        $this->delete(models\Setting::tableName(), ['name' => 'easyii_version']);
        $this->insert(models\Setting::tableName(), [
            'name' => 'easyii_version',
            'value' => self::VERSION,
            'title' => 'EasyiiCMS version',
            'visibility' => models\Setting::VISIBLE_NONE
        ]);
    }

    public function down()
    {
        $this->dropTable(models\Admin::tableName());
        $this->dropTable(models\LoginForm::tableName());
        $this->dropTable(models\Module::tableName());
        $this->dropTable(models\Photo::tableName());
        $this->dropTable(models\Setting::tableName());

        $this->dropTable(Carousel::tableName());
        $this->dropTable(catalog\models\Category::tableName());
        $this->dropTable(catalog\models\Item::tableName());
        $this->dropTable(article\models\Category::tableName());
        $this->dropTable(article\models\Item::tableName());
        $this->dropTable(Feedback::tableName());
        $this->dropTable(File::tableName());
        $this->dropTable(gallery\models\Category::tableName());
        $this->dropTable(Guestbook::tableName());
        $this->dropTable(News::tableName());
        $this->dropTable(Page::tableName());
        $this->dropTable(Subscriber::tableName());
        $this->dropTable(History::tableName());
        $this->dropTable(Text::tableName());
    }
}
