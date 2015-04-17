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
        $album = Album::find()->where(['or', 'album_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $album ? new AlbumObject($album) : null;
    }
}