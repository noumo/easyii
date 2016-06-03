<?php
namespace yii\easyii\components;

use yii\web\UrlManager;

class LangUrlManager extends UrlManager
{
    private $currentLanguage;

    public $languages = ['ru' => 'ru-RU', 'en' => 'en-US'];

    /**
     * Parses the user request.
     *
     */
    public function parseRequest($request)
    {
        $session = \Yii::$app->session;
        if ($this->enablePrettyUrl) {
            $urlInfo = $request->getUrl();
            $urlComponents = explode('/',$urlInfo);
            if ( isset($urlComponents[1]) && in_array($urlComponents[1],array_keys($this->languages))) {
                $this->currentLanguage = $urlComponents[1];
                $modifyRequest = new \yii\web\Request();
                $modifyRequest->setUrl( substr($urlInfo,strlen($urlComponents[1])+1) );
            }
        }
        if (!$this->currentLanguage) {
            $sessionLanguage = $session->get('language');
            $this->currentLanguage = isset($sessionLanguage) ? $sessionLanguage : substr(\Yii::$app->language,0,2) ;
        }
        if ( $session->get('language') != $this->currentLanguage ) {
            $session->set('language',$this->currentLanguage);
        }
        \Yii::$app->language = $this->languages[$this->currentLanguage];
        return parent::parseRequest(isset($modifyRequest) ? $modifyRequest : $request);
    }
    /**
     * Creates a URL using the given route and query parameters.
     * Add lang param before route
     */
    public function createUrl($params)
    {
        $params = array_merge(['language'=> $this->currentLanguage],(array)$params);
        $lang = $params['language'];
        if ($this->enablePrettyUrl)
            unset($params['language']);
        $url = parent::createUrl($params);
        return $this->enablePrettyUrl ? '/'.$lang . ($url === '/' ? '' : $url ) : $url;
    }

}
