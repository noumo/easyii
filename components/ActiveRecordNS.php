<?php
namespace yii\easyii\components;

class ActiveRecordNS extends ActiveRecord
{
    public static function find()
    {
        return new ActiveQueryNS(get_called_class());
    }

    public static function getTree()
    {
        $collection = self::find()->sort()->asArray()->all();
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                unset($item['lft'], $item['rgt'], $item['status'], $item['order_num']);
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while($l > 0 && $stack[$l - 1]['depth'] >= $item['depth']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];

                } else {
                    // Add node to parent
                    $item['parent'] = $stack[$l - 1]['category_id'];
                    $i = count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }
}