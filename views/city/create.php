<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\City $model */

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Ajouter une ville';
$this->params['breadcrumbs'][] = ['label' => 'Villes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <div class="container bg-light rounded">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
        <p><br></p>
    </div>

</div>
