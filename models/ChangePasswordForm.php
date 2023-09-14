<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $id;
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'confirmPassword'], 'required'],
            ['currentPassword', 'validateCurrentPassword'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function validateCurrentPassword($attribute, $params)
    {
        if (!Yii::$app->security->validatePassword($this->currentPassword, Yii::$app->user->identity->password)) {
            $this->addError($attribute, 'Le mot de passe actuel est incorrect.');
        }
    }

    public function changePassword($id){
        $user = Users::findOne($id);
        $user->password = Users::hashPassword($this->newPassword);
        $user->save();

        return $user->password;
    }
}
