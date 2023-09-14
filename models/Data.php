<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data".
 *
 * @property int $id
 * @property float $value_min
 * @property float $value_max
 * @property string $datetime
 * @property int $id_city
 *
 * @property City $city
 */
class Data extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value_min', 'value_max','datetime', 'id_city'], 'required'],
            [['value_min'], 'number'],
            [['value_max'], 'number'],
            [['datetime'], 'safe'],
            [['id_city'], 'integer'],
            [['id_city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['id_city' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value_min' => 'Value Min',
            'value_max' => 'Value Max',
            'datetime' => 'Datetime',
            'cityName' => 'Ville',
            'id_city' => 'Id City',
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

    public function getCityName()
    {
        return $this->city->name;
    }
}
