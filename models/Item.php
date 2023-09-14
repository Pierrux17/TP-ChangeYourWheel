<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $name
 *
 * @property User[] $users
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id_item' => 'id']);
        // return $this->hasMany(Users::class, ['id' => 'id_user'])
        // ->viaTable('user', ['id_item' => 'id']);
    }
}
