<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 11:17
 */

namespace app\models\auth;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $firstName;
    public $secondName;
    public $patronymic;

    public $email;
    public $password;

    public $numberTrack;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'secondName', 'patronymic', 'numberTrack'], 'string'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Электронная почта уже занята.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();

        $user->email = $this->email;

        $user->first_name = $this->firstName;
        $user->second_name = $this->secondName;
        $user->patronymic = $this->patronymic;

        $user->number_track = $this->numberTrack;


        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'firstName' => 'Имя',
            'secondName' => 'Фамилия',
            'patronymic' => 'Отчество',
            'numberTrack' => 'Номер маршрута',
            'password' => 'Пароль',
        ];
    }
}