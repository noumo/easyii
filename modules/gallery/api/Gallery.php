<?php
namespace yii\easyii\modules\gallery\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;

use yii\easyii\widgets\Colorbox;
use yii\easyii\models\Photo;
use yii\easyii\modules\gallery\models\Album;

class Gallery extends \yii\easyii\components\API
{
    private $_albums;
    private $_adp;
    private $_albumOptions = [
        'pageSize' => 20,
    ];
    private $_albumsOptions = [
        'photos' => 0,
        'pageSize' => 20,
        'where' => '',
    ];

    public function api_album($id_slug, $options = [])
    {
        if(!isset($this->_albums[$id_slug])) {
            if (is_array($options) && count($options)) {
                $this->_albumOptions =  array_merge($this->_albumOptions, $options);
            }
            $this->_albums[$id_slug] = $this->findAlbum($id_slug);
        }
        return $this->_albums[$id_slug];
    }

    public function api_photo($id)
    {
        $photo = Photo::findOne($id);
        return $photo ? $photo->image : null;
    }

    public function api_albums($options = [])
    {
        if(is_array($options) && count($options)){
            $this->_albumsOptions = array_merge($this->_albumsOptions, $options);
        }

        return $this->findAlbums();
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result[] = $this->parsePhotos(Photo::find()->where(['model' => Album::className()])->sort()->all());

        if($limit > 1){
            return $result;
        }else{
            $this->_last = $result[0];
            return $this->_last;
        }
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    public function api_colorbox($options = [])
    {
        Colorbox::widget([
            'selector' => '.easyii-box',
            'options' => $options
        ]);
    }

    private function findAlbum($id_slug)
    {
        $album = Album::find()->where(['or', 'album_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        if(!$album){
            return $this->notFound($id_slug);
        }

        if($album->status != Album::STATUS_ON){
            return Yii::$app->user->isGuest ? $this->createObject('') : $this->createObject($this->errorText('ALBUM IS OFF'));
        }

        $this->_adp = new ActiveDataProvider([
            'query' => Photo::find()->where(['model' => Album::className(), 'item_id' => $album->primaryKey])->sort(),
            'pagination' => [
                'pageSize' => $this->_albumOptions['pageSize']
            ]
        ]);

        $albumObject = $this->parseAlbum($album);
        $albumObject->photos = $this->parsePhotos($this->_adp->models);

        $albumObject->seo_h1 = $album->seo_h1;
        $albumObject->seo_title = $album->seo_title;
        $albumObject->seo_keywords = $album->seo_keywords;
        $albumObject->seo_description = $album->seo_description;

        return $albumObject;
    }

    private function findAlbums()
    {
        $result = [];

        $query = Album::find()->status(Album::STATUS_ON)->sort();

        if($this->_albumsOptions['where']){
            $query->andWhere($this->_albumsOptions['where']);
        }

        if($this->_albumsOptions['photos'] > 0){
            $query->with('photos');
        }

        $this->_adp = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->_albumsOptions['pageSize']
            ]
        ]);

        foreach($this->_adp->models as $album){
            $albumObject = $this->parseAlbum($album);
            if($this->_albumsOptions['photos'] > 0) {
                $albumObject->photos = $this->parsePhotos($album->photos, $this->_albumsOptions['photos']);
            }

            $result[] = $albumObject;
        }
        return $result;
    }

    private function parseAlbum($album)
    {
        if(LIVE_EDIT){
            $album->title = $this->wrapLiveEdit($album->title, 'a/edit/'.$album->primaryKey);
        }
        return $this->createObject($album->attributes);
    }

    private function parsePhotos($photos, $limit = null)
    {
        $result = [];
        $admin = Yii::$app->getModule('admin');

        foreach($photos as $photo)
        {
            $temp = new \stdClass();
            $temp->id = $photo->primaryKey;
            $temp->image = $photo->image;
            $temp->thumb = $photo->thumb;
            $temp->box = '<a class="easyii-box" href="'.$photo->image.'" rel="album-'.$photo->item_id.'" title="'.$photo->description.'"><img src="'.$photo->thumb.'"></a>';

            if(LIVE_EDIT){
                $temp->box = $this->wrapLiveEdit($temp->box, 'a/photos/'.$photo->item_id.'#'.'photo-'.$photo->primaryKey);
            }

            $result[] = $temp;

            if($limit && --$limit <= 0) break;
        }
        return $result;
    }

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
        elseif(preg_match(Album::$slugPattern, $id_slug)){
            return $this->createObject('<a href="/admin/gallery/a/create/?slug='.$id_slug.'" target="_blank">'.Yii::t('easyii/gallery/api', 'Create album').'</a>');
        }
        else{
            return $this->createObject($this->errorText('WRONG ALBUM IDENTIFIER'));
        }
    }
}