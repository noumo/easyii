<?php
namespace yii\easyii\modules\gallery\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use yii\easyii\models\Photo;
use yii\easyii\widgets\Fancybox;
use yii\easyii\modules\gallery\models\Album;

class Gallery extends \yii\easyii\components\API
{
    private $_adp;
    private $_last;
    private $_items;
    private $_albums;

    public function api_items($options = [])
    {
        if (!$this->_items) {
            $this->_items = [];

            $this->_adp = new ActiveDataProvider([
                'query' => Album::find()->with('seo')->orderBy('time DESC'),
                'pagination' => $options
            ]);

            foreach ($this->_adp->models as $model) {
                $this->_items[] = new AlbumObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug)
    {
        if (!isset($this->_albums[$id_slug])) {
            $this->_albums[$id_slug] = $this->findAlbum($id_slug);
        }
        return $this->_albums[$id_slug];
    }

    public function api_last($limit = 1)
    {
        if ($limit === 1 && $this->_last) {
            return $this->_last;
        }

        $result = [];
        foreach (Album::find()->with('seo')->sort()->limit($limit)->all() as $item) {
            $result[] = new AlbumObject($item);
        }

        if ($limit > 1) {
            return $result;
        } else {
            $this->_last = $result[0];
            return $this->_last;
        }
    }

    public function api_plugin($options = [])
    {
        Fancybox::widget([
            'selector' => '.easyii-box',
            'options' => $options
        ]);
    }

    public function api_photo($id)
    {
        $photo = Photo::findOne($id);
        return $photo ? $photo->image : null;
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findAlbum($id_slug)
    {
        $album = Album::find()->where(['or', 'file_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $album ? new AlbumObject($album) : null;
    }
}





























=======

    private function createObject($data)
    {
        $is_string = !is_array($data);

        return (object)[
            'id' => $is_string ? '' : $data['album_id'],
            'title' => $is_string ? $data : $data['title'],
            'thumb' => $is_string ? '' : $data['thumb'],
            'slug' => $is_string ? '' : $data['slug'],
            'photos' => [],
            'empty' => $is_string ? true : false
        ];
    }

    private function notFound($id_slug)
    {
        if(Yii::$app->user->isGuest) {
            return $this->createObject('');
        }

        elseif(preg_match(Album::$SLUG_PATTERN, $id_slug)){

            return $this->createObject('<a href="' . Url::to(['/admin/gallery/a/create', 'slug' => $id_slug]) . '" target="_blank">'.Yii::t('easyii/gallery/api', 'Create album').'</a>');
        }
        else{
            return $this->createObject($this->errorText('WRONG ALBUM IDENTIFIER'));
        }
    }
>>>>>>> .theirs
}