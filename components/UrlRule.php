<?php
namespace yii\easyii\components;

class UrlRule extends \yii\web\UrlRule
{
    public function init()
    {
        parent::init();
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    public function createUrl($manager, $route, $params)
    {
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        return false;
        $parts = explode('/', $request->getPathInfo());
        $action = $parts[1];
        $params = [];

        if($parts[0] === '' || $parts[0] === 'debug'){
            return false;
        }

        if($parts[0] === 'admin'){
            if($action === 'm') {
                $action = (string)$parts[3] ?: 'index';
                if (sizeof($parts) > 4) {
                    if (is_numeric($parts[4]) && $parts[4] > 0) {
                        $params['id'] = $parts[4];
                    }
                }
                return [$parts[2] . '/admin/' . $action, $params];
            }
            else{
                $controller = (string)$parts[1] ?: 'default';
                $action = (string)$parts[2] ?: 'index';
                if (sizeof($parts) > 3) {
                    if (is_numeric($parts[3]) && $parts[3] > 0) {
                        $params['id'] = $parts[3];
                    }
                }
                return ['/admin/'. $controller . '/' . $action, $params];
            }
        }
        else{
            if(is_numeric($action) && $action > 0){
                $params['id'] = $action;
                $action = 'view';
            }
            elseif(sizeof($parts) > 2){
                if(is_numeric($parts[2]) && $parts[2] > 0) {
                    $params['id'] = $parts[2];
                }
            }
            return [$parts[0].'/'.$action, $params];
        }
    }
}