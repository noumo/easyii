<?php

namespace yii\easyii\components;

use Yii;

trait TreeTrait
{
	static $TREE = [];

	/**
	 * Get cached tree structure of category objects
	 * @return array
	 */
	public static function tree()
	{
		$cache = Yii::$app->cache;
		$key = static::tableName().'_tree';

		$tree = $cache->get($key);
		if(!$tree){
			$tree = static::generateTree();
			$cache->set($key, $tree, 3600);
		}
		return $tree;
	}

	/**
	 * Generates tree from categories
	 * @return array
	 */
	public static function generateTree()
	{
		$collection = static::find()->with('seo')->sort()->asArray()->all();
		$trees = array();
		$l = 0;

		if (count($collection) > 0) {
			// Node Stack. Used to help building the hierarchy
			$stack = array();

			foreach ($collection as $node) {
				$item = $node;
				unset($item['lft'], $item['rgt'], $item['order_num']);
				$item['children'] = array();

				// Number of stack items
				$l = count($stack);

				// Check if we're dealing with different levels
				while($l > 0 && $stack[$l - 1]->depth >= $item['depth']) {
					array_pop($stack);
					$l--;
				}

				// Stack is empty (we are inspecting the root)
				if ($l == 0) {
					// Assigning the root node
					$i = count($trees);
					$trees[$i] = (object)$item;
					$stack[] = & $trees[$i];

				} else {
					// Add node to parent
					$item['parent'] = $stack[$l - 1]->category_id;
					$i = count($stack[$l - 1]->children);
					$stack[$l - 1]->children[$i] = (object)$item;
					$stack[] = & $stack[$l - 1]->children[$i];
				}
			}
		}

		return $trees;
	}
}