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

class m000009_100000_update extends \yii\db\Migration
{
    const VERSION = 0.91;

    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    
    public function up()
    {
        //ENTITY MODULE
        $this->createTable(entity\models\Category::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'description' => $this->string(1024),
            'image_file' => $this->string(128),
            'fields' => $this->text()->notNull(),
            'slug' => $this->string(128),
            'cache' => $this->boolean()->notNull()->defaultValue(1),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);
        $this->createIndex('slug', entity\models\Category::tableName(), 'slug', true);

        $this->createTable(entity\models\Item::tableName(), [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string(128)->notNull(),
            'data' => $this->text()->notNull(),
            'order_num' => $this->integer(),
            'status' => $this->boolean()->defaultValue(1)
        ], $this->engine);

        $language = Data::getLocale();

        $this->insert(models\Module::tableName(), [
            'name' => 'entity',
            'title' => (!empty(EntityModule::$installConfig['title'][$language]) ? EntityModule::$installConfig['title'][$language] : EntityModule::$installConfig['title']['en']),
            'class' => EntityModule::className(),
            'icon' => 'asterisk',
            'settings' => json_encode((new EntityModule(100))->settings),
            'order_num' => 95,
            'status' => models\Module::STATUS_ON
        ]);

        $this->addColumn(yii\easyii\modules\catalog\models\Category::tableName(), 'description', $this->string(1024) . ' AFTER title');
        $this->addColumn(yii\easyii\modules\article\models\Category::tableName(), 'description', $this->string(1024) . ' AFTER title');
        $this->addColumn(yii\easyii\modules\gallery\models\Category::tableName(), 'description', $this->string(1024) . ' AFTER title');

        $this->renameColumn(catalog\models\Category::tableName(), 'image', 'image_file');
        $this->renameColumn(catalog\models\Item::tableName(), 'image', 'image_file');
        $this->renameColumn(article\models\Category::tableName(), 'image', 'image_file');
        $this->renameColumn(article\models\Item::tableName(), 'image', 'image_file');
        $this->renameColumn(gallery\models\Category::tableName(), 'image', 'image_file');
        $this->renameColumn(News::tableName(), 'image', 'image_file');
        $this->renameColumn(Carousel::tableName(), 'image', 'image_file');
        $this->renameColumn(models\Photo::tableName(), 'image', 'image_file');

        $this->updateFilePath(catalog\models\Category::className());
        $this->updateFilePath(catalog\models\Item::className());
        $this->updateFilePath(article\models\Category::className());
        $this->updateFilePath(article\models\Item::className());
        $this->updateFilePath(News::className());
        $this->updateFilePath(Carousel::className());
        $this->updateFilePath(models\Photo::className());
        $this->updateFilePath(file\models\File::className(), 'file');


        $this->registerI18n();
        $this->insert(Setting::tableName(), [
            'name' => 'image_max_width',
            'value' => 1900,
            'title' => Yii::t('easyii/install', 'Max image width on upload which will not resize'),
            'visibility' => Setting::VISIBLE_ALL
        ]);

        $this->insert(Setting::tableName(), [
            'name' => 'redactor_plugins',
            'value' => 'imagemanager, filemanager, table, fullscreen',
            'title' => Yii::t('easyii/install', 'List of Redactor Widget plugins separated with comma'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        $this->insert(Setting::tableName(), [
            'name' => 'ga_service_email',
            'value' => '',
            'title' => Yii::t('easyii/install', 'Google analytics service account email'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        $this->insert(Setting::tableName(), [
            'name' => 'ga_profile_id',
            'value' => '',
            'title' => Yii::t('easyii/install', 'Google analytics profile id'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        $this->insert(Setting::tableName(), [
            'name' => 'ga_p12_file',
            'value' => '',
            'title' => Yii::t('easyii/install', 'Path to Google analytics service account p12 key file'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        $this->insert(Setting::tableName(), [
            'name' => 'gm_api_key',
            'value' => '',
            'title' => Yii::t('easyii/install', 'Google Maps API key'),
            'visibility' => Setting::VISIBLE_ROOT
        ]);

        MigrationHelper::appendModuleSettings('article', [
            'categorySlugImmutable' => false,
            'categoryDescription' => true,
            'itemSlugImmutable' => false
        ]);
        MigrationHelper::appendModuleSettings('catalog', [
            'categorySlugImmutable' => false,
            'categoryDescription' => true,
            'itemSlugImmutable' => false
        ]);
        MigrationHelper::appendModuleSettings('faq', [
            'questionHtmlEditor' => true
        ]);
        MigrationHelper::appendModuleSettings('faq', [
            'answerHtmlEditor' => true
        ]);
        MigrationHelper::appendModuleSettings('faq', [
            'enableTags' => true
        ]);
        MigrationHelper::appendModuleSettings('file', [
            'slugImmutable' => false
        ]);
        MigrationHelper::appendModuleSettings('gallery', [
            'categoryTags' => true,
            'categorySlugImmutable' => false,
            'categoryDescription' => true,
        ]);
        MigrationHelper::appendModuleSettings('news', [
            'slugImmutable' => false,
        ]);
        MigrationHelper::appendModuleSettings('feedback', [
            'enableEmail' => true,
            'enableText' => true,
        ]);

        //UPDATE VERSION
        $this->update(models\Setting::tableName(), ['value' => self::VERSION], ['name' => 'easyii_version']);
    }

    public function down()
    {
        $this->dropTable(entity\models\Category::tableName());
        $this->dropTable(entity\models\Item::tableName());

        $this->renameColumn(catalog\models\Category::tableName(), 'image_file', 'image');
        $this->renameColumn(catalog\models\Item::tableName(), 'image_file', 'image');
        $this->renameColumn(article\models\Category::tableName(), 'image_file', 'image');
        $this->renameColumn(article\models\Item::tableName(), 'image_file', 'image');
        $this->renameColumn(gallery\models\Category::tableName(), 'image_file', 'image');
        $this->renameColumn(models\Photo::tableName(), 'image_file', 'image');
        $this->renameColumn(Carousel::tableName(), 'image_file', 'image');
        $this->renameColumn(News::tableName(), 'image_file', 'image');

        $this->delete(Setting::tableName(), ['name' => 'image_max_width']);
        $this->delete(Setting::tableName(), ['name' => 'redactor_plugins']);

        //UPDATE VERSION
        $this->update(models\Setting::tableName(), ['value' => 0.9], ['name' => 'easyii_version']);

        echo 'New image and file paths cannot be reverted.';
    }

    private function updateFilePath($class, $attribute = 'image_file')
    {
        foreach($class::find()->all() as $model){
            $pieces = explode('/', $model->{$attribute});
            $count = count($pieces);
            if($count > 2){
                $newPath = $pieces[$count - 2] . '/' . $pieces[$count - 1];
                $class::updateAll([$attribute => $newPath], [$model->primaryKey()[0] => $model->primaryKey]);
            }
        }
    }

    private function registerI18n()
    {
        Yii::$app->i18n->translations['easyii/install'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/noumo/easyii/messages',
            'fileMap' => [
                'easyii/install' => 'install.php',
            ]
        ];
    }
}
