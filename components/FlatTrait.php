<?php

namespace yii\easyii\components;

use Yii;
use yii\easyii\models\SeoText;

trait FlatTrait
{
	protected $withSeo = true;

	static $FLAT = [];

	/**
	 * Get cached flat array of category objects
	 * @return array
	 */
	public static function cats()
	{
		$cache = Yii::$app->cache;
		$key = static::tableName().'_flat';

		if(empty(static::$FLAT[$key])) {

			$flat = $cache->get($key);
			if (!$flat) {
				$flat = static::generateFlat();
				$cache->set($key, $flat, 3600);
			}
			foreach($flat as $id => $cat){
				$model = new static([
					'category_id' => $id,
					'parent' => $cat->parent,
					'children' => $cat->children
				]);

				$model->load((array)$cat, '');
				$model->populateRelation('seo', new SeoText($cat->seo));
				$model->setTagNames($cat->tags);
				$model->afterFind();
				static::$FLAT[$key][] = $model;
			}
		}
		return static::$FLAT[$key];
	}

	/**
	 * Generates flat array of categories
	 * @return array
	 */
	public static function generateFlat()
	{
		$collection = static::find()->with(['seo', 'tags'])->sort()->asArray()->all();
		$flat = [];

		if (count($collection) > 0) {
			$depth = 0;
			$lastId = 0;
			foreach ($collection as $node) {
				$node = (object)$node;
				$id = $node->category_id;
				$node->parent = '';

				if($node->depth > $depth){
					$node->parent = $flat[$lastId]->category_id;
					$depth = $node->depth;
				} elseif($node->depth == 0){
					$depth = 0;
				} else {
					if ($node->depth == $depth) {
						$node->parent = $flat[$lastId]->parent;
					} else {
						foreach($flat as $temp){
							if($temp->depth == $node->depth){
								$node->parent = $temp->parent;
								$depth = $temp->depth;
								break;
							}
						}
					}
				}
				$lastId = $id;
				unset($node->lft, $node->rgt);
				$flat[$id] = $node;
			}
		}

		foreach($flat as &$node){
			$node->children = [];
			foreach($flat as $temp){
				if($temp->parent == $node->category_id){
					$node->children[] = $temp->category_id;
				}
			}
			if(is_array($node->tags) && count($node->tags)){
				$tags = [];
				foreach($node->tags as $tag){
					$tags[] = $tag['name'];
				}
				$node->tags = $tags;
			}
		}

		return $flat;
	}
}