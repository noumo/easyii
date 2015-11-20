<?php
namespace yii\easyii\actions;

class MoveAction extends \yii\base\Action
{
    public $model;
    public $attribute;
    public $direction;

	/**
	 * Move category up/down
	 *
	 * @param $id
	 * @return \yii\web\Response
	 * @throws \Exception
	 */
	public function run($id)
	{
		$model = $this->findCategory($id);
		$modelClass = $this->categoryClass;

		$up = $this->direction == 'up';
		$orderDir = $up ? SORT_ASC : SORT_DESC;

		if ($model->depth == 0) {

			$swapCat = $modelClass::find()->where([$up ? '>' : '<', 'order_num', $model->order_num])->orderBy(['order_num' => $orderDir])->one();
			if ($swapCat) {
				$modelClass::updateAll(['order_num' => '-1'], ['order_num' => $swapCat->order_num]);
				$modelClass::updateAll(['order_num' => $swapCat->order_num], ['order_num' => $model->order_num]);
				$modelClass::updateAll(['order_num' => $model->order_num], ['order_num' => '-1']);
				$model->trigger(\yii\db\ActiveRecord::EVENT_AFTER_UPDATE);
			}
		} else {
			$where = [
				'and',
				['tree' => $model->tree],
				['depth' => $model->depth],
				[($up ? '<' : '>'), 'lft', $model->lft]
			];

			$swapCat = $modelClass::find()->where($where)->orderBy(['lft' => ($up ? SORT_DESC : SORT_ASC)])->one();
			if ($swapCat) {
				if ($up) {
					$model->insertBefore($swapCat);
				} else {
					$model->insertAfter($swapCat);
				}

				$swapCat->update();
				$model->update();
			}
		}
		return $this->controller->back();
	}
}