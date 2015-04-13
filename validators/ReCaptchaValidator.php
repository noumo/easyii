<?php
namespace yii\easyii\validators;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\validators\Validator;

use yii\easyii\models\Setting;

class ReCaptchaValidator extends Validator
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const CAPTCHA_RESPONSE_FIELD = 'g-recaptcha-response';

    /** @var boolean Whether to skip this validator if the input is empty. */
    public $skipOnEmpty = false;

    public function init()
    {
        parent::init();


        if ($this->message === null) {
            $this->message = Yii::t('yii', 'The verification code is incorrect.');
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param \yii\web\View $view
     * @return string
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = Yii::t(
            'yii',
            '{attribute} cannot be blank.',
            ['attribute' => $model->getAttributeLabel($attribute)]
        );
        return "(function(messages){if(!grecaptcha.getResponse()){messages.push('{$message}');}})(messages);";
    }

    /**
     * @param string $value
     * @return array|null
     * @throws Exception
     */
    protected function validateValue($value)
    {
        if (!Setting::get('recaptcha_secret')) {
            throw new InvalidConfigException('Required `recatpcha_secret` setting isn\'t set.');
        }

        if (empty($value)) {
            if (!($value = Yii::$app->request->post(self::CAPTCHA_RESPONSE_FIELD))) {
                return [$this->message, []];
            }
        }

        $request = self::SITE_VERIFY_URL . '?' . http_build_query(
                [
                    'secret' => Setting::get('recaptcha_secret'),
                    'response' => $value,
                    'remoteip' => Yii::$app->request->userIP
                ]
            );
        $response = $this->getResponse($request);
        if (!isset($response['success'])) {
            throw new Exception('Invalid recaptcha verify response.');
        }
        return $response['success'] ? null : [$this->message, []];
    }

    /**
     * @param string $request
     * @return mixed
     */
    protected function getResponse($request)
    {
        $response = file_get_contents($request);
        return Json::decode($response, true);
    }
}