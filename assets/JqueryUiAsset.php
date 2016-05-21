<?php

namespace yii\easyii\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery javascript library](http://jquery.com/)
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JqueryUiAsset extends AssetBundle
{
	public $sourcePath = '@bower/jquery-ui/dist';
	public $js = [
		'jquery-ui.js',
	];
}
