<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $zip
 * @property string $name
 * @property float $lat
 * @property float $lon
 * @property int $id_country
 *
 * @property Country $country
 * @property User[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zip', 'name', 'lat', 'lon', 'id_country'], 'required'],
            [['lat'], 'number'],
            [['lon'], 'number'],
            [['id_country'], 'integer'],
            [['zip'], 'string', 'max' => 25],
            [['name'], 'string', 'max' => 255],
            [['id_country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['id_country' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zip' => 'Zip',
            'name' => 'Name',
            'lat' => 'Latitude',
            'lon' => 'Longitude',
            'id_country' => 'Id Country',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'id_country']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryName()
    {
        return $this->country->name;
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['id_city' => 'id']);
    }
}
