<?php
namespace yii\easyii\modules\news\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use yii\easyii\modules\news\models\News as NewsModel;

class News extends \yii\easyii\components\API
{
    private $_adp;
    private $_last;
    private $_options = [
        'pageSize' => 20,
        'where' => '',
    ];

    public function api_all($options = [])
    {
        $result = [];
        if(is_array($options) && count($options)) {
           $this->_options = array_merge($this->_options, $options);
        }

        foreach($this->adp->models as $news){
            $result[] = $this->parseNews($news);
        }
        return $result;
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];
        foreach(NewsModel::find()->status(NewsModel::STATUS_ON)->desc()->limit($limit)->all() as $news){
            $result[] = $this->parseNews($news);
        }
        if(!count($result)) {
            $result[] = $this->createObject('<a href="' . Url::to(['/admin/news/a/create']) . '" target="_blank">'.Yii::t('easyii/news/api', 'Create news').'</a>');
            return $limit > 1 ? $result : $result[0];
        }

        if($limit > 1){
            return $result;
        }else{
            $this->_last = $result[0];
            return $this->_last;
        }
    }

    public function api_get($id)
    {
        if(!($news = NewsModel::find()->where(['or', 'news_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id])->andWhere(['and', 'status=:st'], [':st' => NewsModel::STATUS_ON])->one())){
            return $this->notFound();
        }

        $news->updateCounters(['views' => 1]);

        $result = $this->parseNews($news);
        $result->seo_h1 = $news->seo_h1;
        $result->seo_title = $news->seo_title;
        $result->seo_keywords = $news->seo_keywords;
        $result->seo_description = $news->seo_description;

        return $result;
    }

    public function api_pagination()
    {
        return $this->adp->pagination;
    }

    public function api_pages()
    {
        return LinkPager::widget(['pagination' => $this->adp->pagination]);
    }

    protected function getAdp()
    {
        if(!$this->_adp){
            $query = NewsModel::find()->status(NewsModel::STATUS_ON)->desc();

            if($this->_options['where']){
                $query->andWhere($this->_options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $this->_options['pageSize']
                ]
            ]);
        }
        return $this->_adp;
    }

    private function parseNews($news)
    {
        if(LIVE_EDIT){
            $news->title = $this->wrapLiveEdit($news->title, 'a/edit/'.$news->primaryKey);
            $news->text = $this->wrapLiveEdit($news->text, 'a/edit/'.$news->primaryKey, 'div');
            if($news->short) {
                $news->short = $this->wrapLiveEdit($news->short, 'a/edit/'.$news->primaryKey);
            }
        }
        return $this->createObject($news->attributes);
    }

    private function createObject($data)
    {
        $is_string = !is_array($data);

        return (object)[
            'id' => $is_string ? '' : $data['news_id'],
            'thumb' => $is_string ? '' : $data['thumb'],
            'title' => $is_string ? $data : $data['title'],
            'short' => $is_string ? $data : $data['short'],
            'text' => $is_string ? $data : $data['text'],
            'slug' => $is_string ? '' : $data['slug'],
            'views' => $is_string ? '' : $data['views'],
            'time' => $is_string ? '' : $data['time'],
            'date' => $is_string ? '' : Yii::$app->formatter->asDatetime($data['time'], 'medium'),
            'empty' => $is_string ? true : false
        ];
    }

    private function notFound()
    {
        if(Yii::$app->user->isGuest) {
            return $this->createObject('');
        }
        else{
            return $this->createObject($this->errorText('WRONG NEWS_ID'));
        }
    }
}
