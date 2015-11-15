<?php
namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortAction;
use yii\easyii\components\Controller;
use yii\easyii\modules\content\models\Layout;
use yii\easyii\widgets\Redactor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class LayoutController extends Controller
{
	public $rootActions = ['fields'];

	public function actions()
	{
		$className = Layout::className();
		return [
			'delete' => [
				'class' => DeleteAction::className(),
				'model' => $className,
				'successMessage' => Yii::t('easyii/content', 'Entry deleted')
			],
			'up' => [
				'class' => SortAction::className(),
				'model' => $className,
				'attribute' => 'order_num'
			],
			'down' => [
				'class' => SortAction::className(),
				'model' => $className,
				'attribute' => 'order_num'
			],
		];
	}

	/**
	 * Categories list
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => Layout::find()->sort(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionFields($id)
	{
		if (!($model = Layout::findOne($id)))
		{
			return $this->redirect(['/admin/' . $this->module->id]);
		}

		if (Yii::$app->request->post('save'))
		{
			$fields = Yii::$app->request->post('Field') ?: [];
			$result = [];

			foreach ($fields as $field)
			{
				$temp = json_decode($field);

				if ($temp === null && json_last_error() !== JSON_ERROR_NONE ||
					empty($temp->name) ||
					empty($temp->title) ||
					empty($temp->type) ||
					!($temp->name = trim($temp->name)) ||
					!($temp->title = trim($temp->title)) ||
					!array_key_exists($temp->type, Layout::$fieldTypes)
				)
				{
					continue;
				}
				$options = '';
				if ($temp->type == 'select' || $temp->type == 'checkbox')
				{
					if (empty($temp->options) || !($temp->options = trim($temp->options)))
					{
						continue;
					}
					$options = [];
					foreach (explode(',', $temp->options) as $option)
					{
						$options[] = trim($option);
					}
				}

				$result[] = [
					'name' => \yii\helpers\Inflector::slug($temp->name),
					'title' => $temp->title,
					'type' => $temp->type,
					'options' => $options
				];
			}

			$model->fields = $result;

			if ($model->save())
			{
				$ids = [];
				foreach ($model->children()->all() as $child)
				{
					$ids[] = $child->primaryKey;
				}
				if (count($ids))
				{
					Layout::updateAll(['fields' => json_encode($model->fields)], ['in', 'category_id', $ids]);
				}

				$this->flash('success', Yii::t('easyii/content', 'Layout updated'));
			}
			else
			{
				$this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
			}
			return $this->refresh();
		}
		else
		{
			return $this->render(
				'fields', [
					'model' => $model
				]
			);
		}
	}

	public function actionCreate($slug = null)
	{
		$model = new Layout;

		if ($model->load(Yii::$app->request->post()))
		{
			if (Yii::$app->request->isAjax)
			{
				Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
			else
			{
				if ($model->save())
				{
					$this->flash('success', Yii::t('easyii/content', 'Layout created'));
					return $this->redirect(['/admin/' . $this->module->id . '/layout']);
				}
				else
				{
					$this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
					return $this->refresh();
				}
			}
		}
		else
		{
			if ($slug)
			{
				$model->slug = $slug;
			}

			return $this->render(
				'create', [
					'model' => $model
				]
			);
		}
	}

	public function actionEdit($id)
	{
		$model = Layout::findOne($id);

		if ($model === null)
		{
			$this->flash('error', Yii::t('easyii', 'Not found'));
			return $this->redirect(['/admin/' . $this->module->id]);
		}

		if ($model->load(Yii::$app->request->post()))
		{
			if (Yii::$app->request->isAjax)
			{
				Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
			else
			{
				if ($model->save())
				{
					$this->flash('success', Yii::t('easyii/content', 'Layout updated'));
				}
				else
				{
					$this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
				}
				return $this->refresh();
			}
		}
		else
		{
			return $this->render(
				'edit', [
					'model' => $model,
				]
			);
		}
	}
}