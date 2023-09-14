<?php

use app\models\Data;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DataSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'value_min',
            'value_max',
            [
                'attribute' => 'datetime',
                'format' => 'raw',
                'value' => function ($model) {
                    $date = Yii::$app->formatter->asDate($model->datetime, 'php:Y-m-d');
                    return date('Y-m-d', strtotime($date . ' +1 day'));
                },
            ],
            'cityName',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Data $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
