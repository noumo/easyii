# Get started

At first create a new content item:

- Open the <a href="/admin/content" target="_blank">content module</a>.

- Create a "New" content item with the title "Home" and save it.

- Now insert a header and text.


Now go in your app directory and add following to your SiteController:

```
use yii\easyii\modules\content\api\controllers\ContentController;

class SiteController extends Controller
{
    use ContentController;
	...
```

Checkout your **home** content: [/site/content?id=home](/site/content?id=home).


## Views
To customize the default content view just create the file `app/views/site/default.php` and insert following:

```
<?php
/** 
 * @var \yii\web\View $this
 * @var \yii\easyii\modules\content\api\ItemObject $content
 */
?>

<div class="container">

	<div class="col-md-12 text-center">
		<h1><?= $content->getHeader() ?></h1>

		<?= $content->getContent() ?>
	</div>
</div>
```

You can create for each content a custom view under `app/views/site/` and the view files have to be named with the slug name of the content.
For example the view file for the **home** content is `app/views/site/home.php`

## Custom Fields

In each *layout (see below)* you can configure custom fields and the content item inherited the fields from their layout.
The field values are available as an array and the field name as key, like this: `$content->data->{field-name}` (without the braces)

## Actions
If you want your own action for your content, just named with the slug name of the content. The slug name is each to the action ID, not the action name! 
For example, **index** becomes **actionIndex**, and **hello-world** becomes **actionHelloWorld**.
See more [Yii2 Doku](http://www.yiiframework.com/doc-2.0/guide-structure-controllers.html#inline-actions).

Just add following action for the home content like this: 

```
public function actionHome()
{
	if ($this->content == null) {
	    throw new \yii\web\NotFoundHttpException(\Yii::t('easyii', 'Not found'));
	}
	
	return $this->renderContentView();
}
```

## Layouts

Under [/admin/content/layout](/admin/content/layout) you can create your own layouts. A layout have a title, slug and custom fields.
The template files for the layout will be search under `app/views/layouts/content/`.
If you don't have a layout, you can overwrite the default layout, just create the file `app/views/layouts/content/default.php` and insert following:

```
<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>

<div class="content">
	<?= $content ?>
</div>
```

## Pretty Urls

For pretty url just add the following url rule into the `app/config/web.php`:

````
'components' => [
	'urlManager' => [
		'rules' => [
			'<id:[\d\w]+>' => 'site/content'
		]
	]
]
````

Checkout your **home** content again: [/home](/home)

> Each content is now avaiable via *http://www.example.com/{id or slug}*

## Navigation

After the body begin call `Content::nav()` inside your `layouts/main.php`. It will generate a default bootstrap navigation, with your content items. 

Please check in the backend which content item are activated for the navigation! No content item will be display in the navigation by default.

```
<?php $this->beginBody() ?>

<header>
    <?php \yii\easyii\modules\content\api\Content::nav() ?>
</header>

<?= $content ?>
<?php $this->endBody() ?>
```