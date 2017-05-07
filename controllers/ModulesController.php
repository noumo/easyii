<?php
namespace yii\easyii\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByNumAction;
use yii\easyii\models\CopyModuleForm;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;
use yii\easyii\models\Module;

class ModulesController extends \yii\easyii\components\Controller
{
    public $rootActions = 'all';
    public $modelClass = 'yii\easyii\models\Module';

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'successMessage' => Yii::t('easyii', 'Module deleted')
            ],
            'up' => SortByNumAction::className(),
            'down' => SortByNumAction::className(),
            'on' => ChangeStatusAction::className(),
            'off' => ChangeStatusAction::className(),
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Module::find()->sort(),
        ]);
        Yii::$app->user->setReturnUrl('/admin/modules');

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Module;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Module created'));
                    return $this->redirect(['/admin/modules']);
                } else {
                    $this->flash('error', Yii::t('Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Module updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionSettings($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('Settings')) {
            $model->setSettings(Yii::$app->request->post('Settings'));
            if ($model->save()) {
                $this->flash('success', Yii::t('easyii', 'Module settings updated'));
            } else {
                $this->flash('error', Yii::t('easyii', Yii::t('easyii', 'Update error. {0}', $model->formatErrors())));
            }
            return $this->refresh();
        } else {

            return $this->render('settings', [
                'model' => $model
            ]);
        }
    }

    public function actionRestoreSettings($id)
    {
        $model = $this->findModel($id);
        $model->settings = '';
        $model->save();
        $this->flash('success', Yii::t('easyii', 'Module default settings was restored'));

        return $this->back();
    }

    public function actionCopy($id)
    {
        $module = Module::findOne($id);
        $formModel = new CopyModuleForm();

        if ($module === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect('/admin/modules');
        }
        if ($formModel->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($formModel);
            } else {
                $reflector = new \ReflectionClass($module->class);
                $oldModuleFolder = dirname($reflector->getFileName());
                $oldNameSpace = $reflector->getNamespaceName();
                $oldModuleClass = $reflector->getShortName();

                $newModulesFolder = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'modules';
                $newModuleFolder = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $formModel->name;
                $newNameSpace = 'app\modules\\' . $formModel->name;
                $newModuleClass = ucfirst($formModel->name) . 'Module';

                if (!FileHelper::createDirectory($newModulesFolder, 0755)) {
                    $this->flash('error', 'Cannot create `' . $newModulesFolder . '`. Please check write permissions.');
                    return $this->refresh();
                }

                if (file_exists($newModuleFolder)) {
                    $this->flash('error', 'New module folder `' . $newModulesFolder . '` already exists.');
                    return $this->refresh();
                }

                //Copying module folder
                try {
                    FileHelper::copyDirectory($oldModuleFolder, $newModuleFolder);
                } catch (\Exception $e) {
                    $this->flash('error', 'Cannot copy `' . $oldModuleFolder . '` to `' . $newModuleFolder . '`. Please check write permissions.');
                    return $this->refresh();
                }

                //Renaming module file name
                $newModuleFile = $newModuleFolder . DIRECTORY_SEPARATOR . $newModuleClass . '.php';
                $oldModuleFile = $newModuleFolder . DIRECTORY_SEPARATOR . $reflector->getShortName() . '.php';

                if (!rename($oldModuleFile, $newModuleFile)) {
                    $this->flash('error', 'Cannot rename `' . $newModulesFolder . '`.');
                    return $this->refresh();
                }

                //Renaming module class name
                $moduleFileContent = file_get_contents($newModuleFile);
                $moduleFileContent = str_replace($oldModuleClass, $newModuleClass, $moduleFileContent);
                $moduleFileContent = str_replace('@easyii', '@app', $moduleFileContent);
                $moduleFileContent = str_replace('/' . $module->name, '/' . $formModel->name, $moduleFileContent);
                file_put_contents($newModuleFile, $moduleFileContent);

                //Replacing namespace
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($newModuleFolder)) as $file => $object) {
                    if (!$object->isDir()) {
                        $fileContent = file_get_contents($file);
                        $fileContent = str_replace($oldNameSpace, $newNameSpace, $fileContent);
                        $fileContent = str_replace($oldModuleClass, $newModuleClass, $fileContent);
                        $fileContent = str_replace("Yii::t('easyii/" . $module->name, "Yii::t('easyii/" . $formModel->name, $fileContent);
                        $fileContent = str_replace("'" . $module->name . "'", "'" . $formModel->name . "'", $fileContent);
                        $fileContent = str_replace('/' . $module->name . '/', '/' . $formModel->name . '/', $fileContent);

                        file_put_contents($file, $fileContent);
                    }
                }

                //Copying module tables
                foreach (glob($newModuleFolder . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . '*.php') as $modelFile) {
                    $baseName = basename($modelFile, '.php');
                    $modelClass = $newNameSpace . '\models\\' . $baseName;

                    $oldTableName = $modelClass::tableName();
                    $newTableName = str_replace($module->name, $formModel->name, $oldTableName);
                    $newTableName = str_replace('easyii', 'app', $newTableName);

                    try {
                        //Drop new table if exists
                        Yii::$app->db->createCommand("DROP TABLE IF EXISTS `$newTableName`;")->execute();

                        //Copy new table
                        Yii::$app->db->createCommand("CREATE TABLE `$newTableName` LIKE `$oldTableName`;")->execute();
                    } catch (\yii\db\Exception $e) {
                        $this->flash('error', 'Copy table error. ' . $e);
                        FileHelper::removeDirectory($newModuleFolder);
                        return $this->refresh();
                    }

                    file_put_contents($modelFile, str_replace($oldTableName, $newTableName, file_get_contents($modelFile)));
                }

                $newModule = new Module([
                    'name' => $formModel->name,
                    'class' => $newNameSpace . '\\' . $newModuleClass,
                    'title' => $formModel->title,
                    'icon' => $module->icon,
                    'settings' => $module->settings,
                    'status' => Module::STATUS_ON,
                ]);

                if ($newModule->save()) {
                    $this->flash('success', 'New module created');
                    return $this->redirect(['/admin/modules/edit', 'id' => $newModule->primaryKey]);
                } else {
                    $this->flash('error', 'Module create error. ' . $newModule->formatErrors());
                    FileHelper::removeDirectory($newModuleFolder);
                    return $this->refresh();
                }
            }
        }

        return $this->render('copy', [
            'model' => $module,
            'formModel' => $formModel
        ]);
    }
}