<?php

namespace yii\easyii\models;

use Yii;
use yii\base\InvalidParamException;
use yii\easyii\components\ActiveRecord;
use yii\easyii\validators\EscapeValidator;

/**
 * Class ResetPassword
 *
 * @property string $token
 * @property string $email
 * @property string $password
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class ResetPassword extends ActiveRecord
{
    const TOKEN_EXPIRE = 3600;

    public static function tableName()
    {
        return 'easyii_reset_password';
    }

    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            [['token', 'password'], 'string'],
            [['email', 'password'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('easyii', 'Email'),
            'password' => Yii::t('easyii', 'Password'),
        ];
    }

    public function resetPassword()
    {
        $user = Admin::findByEmail($this->email);
        if (!$user) {
            throw new InvalidParamException('User not found.');
        }

        if ($user->isRoot()) {
            // Reset root password only in dev mode
            if (YII_ENV_DEV) {
                return $this->resetRootPassword();
            }
        }
        else {
            $user->password = $this->password;
            $user->save();

            $this->ip = $_SERVER['REMOTE_ADDR'];
            $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $this->time = time();

            $this->removeToken();
            return $this->save();
        }

        return false;
    }

    private function resetRootPassword()
    {
        $password_salt = Setting::get('password_salt');
        $root_auth_key = Yii::$app->security->generateRandomString();
        $root_password = sha1($this->password.$root_auth_key.$password_salt);

        /** @var Setting $setting */

        $setting = Setting::findOne(['name' => 'root_auth_key']);
        $setting->value = $root_auth_key;
        $success = $setting->update();

        $setting = Setting::findOne(['name' => 'root_password']);
        $setting->value = $root_password;
        $success = $success && $setting->update();

        return $success;
    }

    /**
     * Finds reset password form by token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByToken($token)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = self::TOKEN_EXPIRE;
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generateToken()
    {
        $this->token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removeToken()
    {
        $this->token = null;
    }
}
