<?php
namespace yii\easyii\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
	public $email;
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
		];
	}

	public function init()
	{
		$this->email = Setting::get('root_email');
	}

	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		$user = Admin::findByEmail($this->email);
		if (!$user) {
			return false;
		}

		/* @var $resetPassword ResetPassword */
		$resetPassword = new ResetPassword();
		$resetPassword->email = $this->email;

		if (!ResetPassword::isTokenValid($resetPassword->token)) {
			$resetPassword->generateToken();
		}

		if (!$resetPassword->save()) {
			return false;
		}

		Yii::$app->mailer->htmlLayout = '@easyii/mails/layouts/html';
		Yii::$app->mailer->textLayout = '@easyii/mails/layouts/text';

		return Yii::$app
			->mailer
			->compose(
				['html' => '@easyii/mails/passwordResetToken-html', 'text' => '@easyii/mails/passwordResetToken-text'],
				['resetPassword' => $resetPassword]
			)
			->setFrom([Setting::get('robot_email') => Yii::$app->name . ' robot'])
			->setTo($this->email)
			->setSubject('Password reset for ' . Yii::$app->name)
			->send();
	}
}