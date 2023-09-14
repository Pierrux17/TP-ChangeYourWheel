<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $mail
 * @property string $auth_key
 * @property string $password
 * @property string|null $password_reset_token
 * @property string $created_at
 * @property string|null $updated_at
 * @property int $id_type_user
 * @property int $id_city
 * @property int $id_habits
 * @property boolean $isMailSend

 *
 * @property City $city
 * @property Habits $habits
 * @property TypeUser $typeUser
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password_hash;
    public $terms;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'mail', 'auth_key', 'created_at', 'id_type_user', 'id_city', 'id_habits'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_type_user', 'id_city', 'id_habits'], 'integer'],
            [['lastname', 'firstname', 'mail', 'password', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['auth_key'], 'unique'],
            [['created_at'], 'unique'],
            [['mail'], 'unique', 'targetClass' => self::class, 'message' => 'Cet email est déjà utilisé.'],
            [['mail'], 'email'],
            [['id_city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['id_city' => 'id']],
            [['id_habits'], 'exist', 'skipOnError' => true, 'targetClass' => Habits::class, 'targetAttribute' => ['id_habits' => 'id']],
            [['id_type_user'], 'exist', 'skipOnError' => true, 'targetClass' => TypeUser::class, 'targetAttribute' => ['id_type_user' => 'id']],
            [['password'], 'required', 'when' => function($model) {
                return $model->isNewRecord;
            }],
            ['terms', 'required', 'requiredValue' => true, 'message' => 'Vous devez accepter la politique de confidentialité et les conditions générales', 'when' => function($model) {
                return $model->isNewRecord;
            }],
            [['isMailSend'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Nom',
            'firstname' => 'Prénom',
            'mail' => 'Mail',
            'auth_key' => 'Auth Key',
            'password' => 'Mot de passe',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'id_type_user' => 'Type d\'utilisateur',
            'id_city' => 'Id Ville',
            'cityName' => 'Ville',
            'id_habits' => 'Habitudes de roulage',
            'isMailSend' => 'Autorisation d\'envoyer des mails',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'id_city']);
    }

    /**
     * Gets query for [[Habits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabits()
    {
        return $this->hasOne(Habits::class, ['id' => 'id_habits']);
    }


    /**
     * Gets query for [[TypeUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeUser()
    {
        return $this->hasOne(TypeUser::class, ['id' => 'id_type_user']);
    }


    /**
     * Finds user by mail
     *
     * @param string $mail
     * @return static|null
     */
    // public static function findByMail($mail)
    // {
    //     foreach (self::$users as $user) {
    //         if (strcasecmp($user['mail'], $mail) === 0) {
    //             return new static($user);
    //         }
    //     }

    //     return null;
    // }

    public static function findByMail($mail)
    {
        return static::findOne(['mail' => $mail]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === static::hashPassword($password);
        // return $this->password === $password;
    }

    /**
     * hash password
     * 
     * Function to create password hash
     * 
     * @param string $password
     */
    public static function hashPassword($password) {
        $salt = "stev37f";
        return md5($password.$salt);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implementation specific to your application
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityName()
    {
        return $this->city->name;
    }

    /**
     * Gets query for [[TypeUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeUserName()
    {
        return $this->typeuser->name;
    }

    /**
     * Gets query for [[Habits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabitsName()
    {
        return $this->habits->name;
    }

    /**
     * Set le type du user
     * @param $id_type_user
     */
    public function setTypeUserId($id_type_user){
        $this->id_type_user = $id_type_user;
    }

    /**
     * Détermine l'admin
     */
    public function isAdmin()
    {
        return $this->id_type_user == 1;
    }
}
