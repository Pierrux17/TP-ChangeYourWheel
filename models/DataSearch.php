<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Data;

/**
 * DataSearch represents the model behind the search form of `app\models\Data`.
 */
class DataSearch extends Data
{
    public $cityName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_city'], 'integer'],
            [['value_min', 'value_max'], 'number'],
            [['datetime', 'cityName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Data::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['city']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'value_min' => $this->value_min,
            'value_max' => $this->value_max,
            'datetime' => $this->datetime,
            'id_city' => $this->id_city,
        ]);

        $query->andFilterWhere(['like', 'city.name', $this->cityName]);

        return $dataProvider;
    }
}
