<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Country $model */

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-create">

    <div class="container bg-light rounded">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
        <p><br></p>
    </div>
</div>
