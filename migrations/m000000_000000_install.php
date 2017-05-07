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
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull(),
            'password' => $this->string(64)->notNull(),
            'auth_key' => $this->string(128),
            'access_token' => $this->string(128),
        ], $this->engine);
        $this->createIndex('access_token', models\Admin::tableName(), 'access_token', true);

        //LOGINFORM
        $this->createTable(models\LoginForm::tableName(), [
            'id' => $this->primaryKey(),
            'username' => $this->string(128),
            'password' => $this->string(128),
            'ip' => $this->string(16),
            'user_agent' => $this->string(1024),
            'time' => $this->integer()->defaultValue(0),
            'success' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        //MODULES
        $this->createTable(models\Module::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'class' => $this->string(128)->notNull(),
            'title' => $this->string(128)->notNull(),
            'icon' => $this->string(32),
            'settings' => $this->text(),
            'notice' => $this->integer()->defaultValue(0),
            'order_num' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);
        $this->createIndex('name', models\Module::tableName(), 'name', true);

        //PHOTOS
        $this->createTable(models\Photo::tableName(), [
            'id' => $this->primaryKey(),
            'class' => $this->string(128)->notNull(),
            'item_id' => $this->integer()->notNull(),
            'image' => $this->string(128)->notNull(),
            'description' => $this->string(1024),
            'order_num' => $this->integer()->defaultValue(0),
        ], $this->engine);
        $this->createIndex('model_item', models\Photo::tableName(), ['class', 'item_id']);

        //SEOTEXT
        $this->createTable(models\SeoText::tableName(), [
            'id' => $this->primaryKey(),
            'class' => $this->string(128)->notNull(),
            'item_id' => $this->integer()->notNull(),
            'h1' => $this->string(255),
            'title' => $this->string(255),
            'keywords' => $this->string(255),
            'description' => $this->string(255),
        ], $this->engine);
        $this->createIndex('model_item', models\SeoText::tableName(), ['class', 'item_id'], true);

        //SETTINGS
        $this->createTable(models\Setting::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'title' => $this->string(128)->notNull(),
            'value' => $this->string(1024),
            'visibility' => $this->boolean()->defaultValue(0),
        ], $this->engine);
        $this->createIndex('name', models\Setting::tableName(), 'name', true);

        //CAROUSEL MODULE
        $this->createTable(Carousel::tableName(), [
            'id' => $this->primaryKey(),
            'image' => $this->string(128)->notNull(),
            'link' => $this->string(255),
            'title' => $this->string(255),
            'text' => $this->string(1024),
            'order_num' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
        ], $this->engine);

        //CATALOG MODULE
        $this->createTable(catalog\models\Category::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128),
            'fields' => $this->text(),
            'slug' => $this->string(128),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'order_num' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', catalog\models\Category::tableName(), 'slug', true);

        $this->createTable(catalog\models\Item::tableName(), [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'title' => $this->string(128)->notNull(),
            'description' => $this->text(),
            'available' => $this->integer()->defaultValue(1),
            'price' => $this->float()->defaultValue(0),
            'discount' => $this->integer()->defaultValue(0),
            'data' => $this->text(),
            'image' => $this->string(128),
            'slug' => $this->string(128),
            'time' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', catalog\models\Item::tableName(), 'slug', true);

        $this->createTable(catalog\models\ItemData::tableName(), [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer(),
            'name' => $this->string(128)->notNull(),
            'value' => $this->string(1024),
        ], $this->engine);
        $this->createIndex('item_id_name', catalog\models\ItemData::tableName(), ['item_id', 'name']);
        $this->createIndex('value', catalog\models\ItemData::tableName(), 'value(300)');

        //SHOPCART MODULE
        $this->createTable(shopcart\models\Order::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'address' => $this->string(1024),
            'phone' => $this->string(64),
            'email' => $this->string(128),
            'comment' => $this->string(1024),
            'remark' => $this->string(1024),
            'access_token' => $this->string(32),
            'ip' => $this->string(16),
            'time' => $this->integer()->defaultValue(0),
            'new' => $this->boolean()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        $this->createTable(shopcart\models\Good::tableName(), [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'item_id' => $this->integer(),
            'count' => $this->integer(),
            'options' => $this->string(255),
            'price' => $this->float()->defaultValue(0),
            'discount' => $this->integer()->defaultValue(0),
        ], $this->engine);

        //FEEDBACK MODULE
        $this->createTable(Feedback::tableName(), [
            'id' => $this->primaryKey(),
            'name' => Schema::TYPE_STRING,
            'email' => $this->string(128)->notNull(),
            'phone' => $this->string(64),
            'title' => $this->string(128),
            'text' => $this->text()->notNull(),
            'answer_subject' => $this->string(128),
            'answer_text' => $this->text(),
            'time' => $this->integer()->defaultValue(0),
            'ip' => $this->string(16),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        //FILE MODULE
        $this->createTable(File::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'file' => $this->string(255)->notNull(),
            'size' => $this->integer()->defaultValue(0),
            'slug' => $this->string(128),
            'downloads' => $this->integer()->defaultValue(0),
            'time' => $this->integer()->defaultValue(0),
            'order_num' => $this->integer(),
        ], $this->engine);
        $this->createIndex('slug', File::tableName(), 'slug', true);

        //GALLERY MODULE
        $this->createTable(gallery\models\Category::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128),
            'slug' => $this->string(128),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', gallery\models\Category::tableName(), 'slug', true);

        //GUESTBOOK MODULE
        $this->createTable(Guestbook::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'title' => $this->string(128),
            'text' => $this->text()->notNull(),
            'answer' => $this->text(),
            'email' => $this->string(128),
            'time' => $this->integer()->defaultValue(0),
            'ip' => $this->string(16),
            'new' => $this->boolean()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ], $this->engine);

        //NEWS MODULE
        $this->createTable(News::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128),
            'short' => $this->string(1024),
            'text' => $this->text(),
            'slug' => $this->string(128),
            'time' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', News::tableName(), 'slug', true);

        //ARTICLE MODULE
        $this->createTable(article\models\Category::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128),
            'order_num' => $this->integer(),
            'slug' => $this->string(128),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', article\models\Category::tableName(), 'slug', true);

        $this->createTable(article\models\Item::tableName(), [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'title' => $this->string(128)->notNull(),
            'image' => $this->string(128),
            'short' => $this->string(1024),
            'text' => $this->text(),
            'slug' => $this->string(128),
            'time' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', article\models\Item::tableName(), 'slug', true);

        //PAGE MODULE
        $this->createTable(Page::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'text' => $this->text(),
            'slug' => $this->string(128)
        ], $this->engine);
        $this->createIndex('slug', Page::tableName(), 'slug', true);

        //FAQ MODULE
        $this->createTable(Faq::tableName(), [
            'id' => $this->primaryKey(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'order_num' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        //SUBSCRIBE MODULE
        $this->createTable(Subscriber::tableName(), [
            'id' => $this->primaryKey(),
            'email' => $this->string(128)->notNull(),
            'ip' => $this->string(16),
            'time' => $this->integer()->defaultValue(0)
        ], $this->engine);
        $this->createIndex('email', Subscriber::tableName(), 'email', true);

        $this->createTable(History::tableName(), [
            'id' => $this->primaryKey(),
            'subject' => $this->string(128)->notNull(),
            'body' => $this->text(),
            'sent' => $this->integer()->defaultValue(0),
            'time' => $this->integer()->defaultValue(0)
        ], $this->engine);

        //TEXT MODULE
        $this->createTable(Text::tableName(), [
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'slug' => $this->string(128)
        ], $this->engine);
        $this->createIndex('slug', Text::tableName(), 'slug', true);

        //Tags
        $this->createTable(models\Tag::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'frequency' => $this->integer()->defaultValue(0)
        ], $this->engine);
        $this->createIndex('name', models\Tag::tableName(), 'name', true);

        $this->createTable(models\TagAssign::tableName(), [
            'class' => $this->string(128)->notNull(),
            'item_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
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
