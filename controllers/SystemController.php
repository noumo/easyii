<?php
namespace yii\easyii\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\helpers\WebConsole;
use yii\easyii\models\LoginForm;
use yii\easyii\models\Setting;

class SystemController extends \yii\easyii\components\Controller
{
    public $rootActions = 'all';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        $result = WebConsole::migrate();

        Setting::set('easyii_version', \yii\easyii\AdminModule::VERSION);
        Yii::$app->cache->flush();

        return $this->render('update', ['result' => $result]);
    }

    public function actionFlushCache()
    {
        Yii::$app->cache->flush();
        $this->flash('success', Yii::t('easyii', 'Cache flushed'));
        return $this->back();
    }

    public function actionClearAssets()
    {
        foreach(glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset){
            if(is_link($asset)){
                unlink($asset);
            } elseif(is_dir($asset)){
                $this->deleteDir($asset);
            } else {
                unlink($asset);
            }
        }
        $this->flash('success', Yii::t('easyii', 'Assets cleared'));
        return $this->back();
    }

    public function actionLiveEdit($id)
    {
        Yii::$app->session->set('easyii_live_edit', $id);
        $this->back();
    }

    public function actionLogs()
    {
        $data = new ActiveDataProvider([
            'query' => LoginForm::find()->desc(),
        ]);

        return $this->render('logs', [
            'data' => $data
        ]);
    }

    private function deleteDir($directory)
    {
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        return rmdir($directory);
    }
}