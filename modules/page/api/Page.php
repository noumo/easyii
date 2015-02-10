<?php
namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\modules\page\models\Page as PageModel;

class Page extends \yii\easyii\components\API
{
    private $_pages = [];

    public function api_get($id_slug)
    {
        if(!isset($this->_pages[$id_slug])){
            $this->_pages[$id_slug] = $this->findPage($id_slug);
        }
        return $this->_pages[$id_slug];
    }

    private function findPage($id_slug)
    {
        $page = PageModel::find()->where(['or', 'page_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $page ? $this->parsePage($page) : $this->notFound($id_slug);
    }

    private function parsePage($page)
    {
        if(LIVE_EDIT){
            $page->title = $this->wrapLiveEdit($page->title, 'a/edit/'.$page->primaryKey);
            $page->text = $this->wrapLiveEdit($page->text, 'a/edit/'.$page->primaryKey, 'div');
        }
        return $this->createObject($page->attributes);
    }

    private function createObject($data)
    {
        $is_string = !is_array($data);

        return (object)[
            'id' => $is_string ? '' : $data['page_id'],
            'title' => $is_string ? $data : $data['title'],
            'text' => $is_string ? $data : $data['text'],
            'slug' => $is_string ? '' : $data['slug'],
            'empty' => $is_string ? true : false
        ];
    }

    private function notFound($id_slug)
    {
        if(Yii::$app->user->isGuest) {
            return $this->createObject('');
        }
        elseif(preg_match(PageModel::$slugPattern, $id_slug)){
            return $this->createObject('<a href="/admin/page/a/create/?slug='.$id_slug.'" target="_blank">'.Yii::t('easyii/page/api', 'Create page').'</a>');
        }
        else{
            return $this->createObject($this->errorText('WRONG PAGE IDENTIFIER'));
        }
    }
}