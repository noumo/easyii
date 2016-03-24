<?php

namespace yii\easyii\modules\content\modules\contentElements;

use Yii;

trait WidgetActionTrait
{
	public function runAction($id, $params)
	{
		$action = $this->createAction($id);
		if ($action === null) {
			throw new \yii\base\InvalidRouteException('Unable to resolve the request: ' . get_class($this) . '/' . $id);
		}

		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		// run the action
		$result = $action->runWithParams($params);

		return $result;
	}

	public function createAction($id)
	{
		$methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
		if (method_exists($this, $methodName)) {
			$method = new \ReflectionMethod($this, $methodName);
			if ($method->isPublic() && $method->getName() === $methodName) {
				return new \yii\base\InlineAction($id, $this, $methodName);
			}
		}
	}

	public function bindActionParams($action, $params)
	{
		if ($action instanceof \yii\base\InlineAction) {
			$method = new \ReflectionMethod($this, $action->actionMethod);
		} else {
			$method = new \ReflectionMethod($action, 'run');
		}

		$args = [];
		$missing = [];
		$actionParams = [];
		foreach ($method->getParameters() as $param) {
			$name = $param->getName();
			if (array_key_exists($name, $params)) {
				if ($param->isArray()) {
					$args[] = $actionParams[$name] = (array) $params[$name];
				} elseif (!is_array($params[$name])) {
					$args[] = $actionParams[$name] = $params[$name];
				} else {
					throw new \yii\web\BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
						'param' => $name,
					]));
				}
				unset($params[$name]);
			} elseif ($param->isDefaultValueAvailable()) {
				$args[] = $actionParams[$name] = $param->getDefaultValue();
			} else {
				$missing[] = $name;
			}
		}

		if (!empty($missing)) {
			throw new \yii\web\BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
				'params' => implode(', ', $missing),
			]));
		}

		return $args;
	}
}