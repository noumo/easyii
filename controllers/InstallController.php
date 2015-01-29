<?php
namespace yii\easyii\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

use yii\easyii\AdminModule;
use yii\easyii\models\InstallForm;
use yii\easyii\models\LoginForm;
use yii\easyii\models\Module;
use yii\easyii\models\Setting;

class InstallController extends \yii\web\Controller
{
    public $layout = 'empty';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->registerI18n();
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        if(!$this->checkDbConnection()){
            return $this->showError('Cannot connect to database. Please configure `config/db.php`.');
        }
        if(AdminModule::installed()){
            return $this->showError('EasyiiCMS is already installed. If you want to reinstall easyiiCMS, please drop all tables with prefix `easyii_` from your database manually.');
        }

        $installForm = new InstallForm();

        if ($installForm->load(Yii::$app->request->post())) {
            $this->createUploadsDir();
            $this->createAdminTables();
            $this->insertSettings($installForm);
            $this->loadModules();

            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('root_password', $installForm->root_password);

            return $this->redirect('/admin/install/finish');
        }
        else {
            $installForm->robot_email = 'noreply@'.Yii::$app->request->serverName;

            return $this->render('index', [
                'model' => $installForm
            ]);
        }
    }

    public function actionFinish()
    {
        $root_password = Yii::$app->session->getFlash('root_password', null, true);
        if($root_password)
        {
            $loginForm = new LoginForm([
                'username' => 'root',
                'password' => $root_password,
            ]);
            if($loginForm->login()){
                return $this->redirect('/admin/');
            }
        }

        return $this->render('finish');
    }

    private function registerI18n()
    {
        Yii::$app->i18n->translations['easyii/install'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii/messages',
            'fileMap' => [
                'easyii/install' => 'install.php',
            ]
        ];
    }

    private function checkDbConnection()
    {
        try{
            Yii::$app->db->createCommand('SELECT NOW()')->query();
            return true;
        }
        catch(\Exception $e){
            return false;
        }
    }

    private function showError($text)
    {
        return $this->render('error', ['error' => $text]);
    }

    private function createUploadsDir()
    {
        $uploadsDir = Yii::getAlias('@webroot' . DIRECTORY_SEPARATOR . 'uploads');
        $uploadsDirExists = file_exists($uploadsDir);
        if(($uploadsDirExists && !is_writable($uploadsDir)) || (!$uploadsDirExists && !mkdir($uploadsDir, 0777))){
            throw new ServerErrorHttpException('Cannot create uploads folder at `'.$uploadsDir.'` Please check write permissions.');
        }
    }

    private function createAdminTables()
    {
        foreach(FileHelper::findFiles(Yii::getAlias('@easyii') .DIRECTORY_SEPARATOR. 'scheme', ['only' => ['*.sql']]) as $sqlFile){
            $this->createTableFromFile($sqlFile);
        }
    }

    private function insertSettings($installForm)
    {
        $db = Yii::$app->db;
        $password_salt = Yii::$app->security->generateRandomString();
        $root_auth_key = Yii::$app->security->generateRandomString();
        $root_password = sha1($installForm->root_password.$root_auth_key.$password_salt);

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_key',
            'value' => $installForm->recaptcha_key,
            'title' => Yii::t('easyii/install', 'ReCaptcha key'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'password_salt',
            'value' => $password_salt,
            'title' => 'Password salt',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_auth_key',
            'value' => $root_auth_key,
            'title' => 'Root authorization key',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_password',
            'value' => $root_password,
            'title' => Yii::t('easyii/install', 'Root password'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'auth_time',
            'value' => 86400,
            'title' => Yii::t('easyii/install', 'Auth time'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'robot_email',
            'value' => $installForm->robot_email,
            'title' => Yii::t('easyii/install', 'Robot E-mail'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'admin_email',
            'value' => $installForm->admin_email,
            'title' => Yii::t('easyii/install', 'Admin E-mail'),
            'visibility' => Setting::VISIBLE_ALL
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_secret',
            'value' => $installForm->recaptcha_secret,
            'title' => Yii::t('easyii/install', 'ReCaptcha secret'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'toolbar_position',
            'value' => 'top',
            'title' => Yii::t('easyii/install', 'Frontend toolbar position').' ("top" or "bottom")',
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();
    }

    private function loadModules()
    {
        $language = substr(Yii::$app->language, 0, 2);

        foreach(glob(Yii::getAlias('@easyii'). DIRECTORY_SEPARATOR .'modules/*') as $module){
            $moduleName = basename($module);
            $moduleConfig = require($module. DIRECTORY_SEPARATOR .'scheme/module.php');

            foreach(FileHelper::findFiles($module. DIRECTORY_SEPARATOR .'scheme', ['only' => ['*.sql'], 'recursive' => false]) as $sqlFile){
                $this->createTableFromFile($sqlFile);
            }
            $module = new Module();
            $module->name = $moduleName;
            $module->title = !empty($moduleConfig['title'][$language]) ? $moduleConfig['title'][$language] : $moduleConfig['title']['en'];
            $module->icon = $moduleConfig['icon'];
            $module->order_num = $moduleConfig['order_num'];
            $module->status = Module::STATUS_ON;
            $module->save();
        }
    }

    private function createTableFromFile($sqlFile)
    {
        $tableName = 'easyii_'.basename($sqlFile, '.sql');
        Yii::$app->db->createCommand(file_get_contents($sqlFile))->execute();
        if(!Yii::$app->db->createCommand("SHOW TABLES LIKE '".$tableName."'")->queryScalar()){
            throw new ServerErrorHttpException('Cannot create `'.$tableName.'` table. Check your database.');
        }
    }
}