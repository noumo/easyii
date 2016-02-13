<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class GoogleCharts extends Widget
{
    public $email;
    public $ids;
    public $p12;

    public function init()
    {
        parent::init();

        if (empty($this->email)) {
            throw new InvalidConfigException('Required `email` param isn\'t set.');
        }
        if (empty($this->ids)) {
            throw new InvalidConfigException('Required `ids` param isn\'t set.');
        }
        if (empty($this->p12) || !file_exists(Yii::getAlias($this->p12))) {
            throw new InvalidConfigException('Required `p12` file path isn\'t set or file not exists.');
        }
    }

    public function run()
    {
        $client = new \Google_Client();
        $client->setClassConfig('Google_Cache_File', ['directory' => Yii::getAlias('@runtime')]);
        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials($this->email, ['https://www.googleapis.com/auth/analytics.readonly'], file_get_contents(Yii::getAlias($this->p12))));
        if($client->getAuth()->isAccessTokenExpired()) {
            try{
                $client->getAuth()->refreshTokenWithAssertion();
            } catch(\Exception $e){
                if(IS_ROOT){
                    echo $e->getMessage();
                }
                return;
            }
        }
        $decodedTokenObject = json_decode($client->getAccessToken());

        if(!json_last_error() && !empty($decodedTokenObject->access_token)) {
            $this->view->registerJs("
                (function(w,d,s,g,js,fs){
                    g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
                    js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
                    js.src='https://apis.google.com/js/platform.js';
                    fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
                }(window,document,'script'));
            ", \yii\web\View::POS_HEAD);

            $period = (int)Yii::$app->request->get('period');

            echo $this->render('google_charts', [
                'access_token' => $decodedTokenObject->access_token,
                'period' => $period
            ]);
        }
    }
}