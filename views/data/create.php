<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Data $model */

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Create Data';
$this->params['breadcrumbs'][] = ['label' => 'Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-create">

    <div class="container bg-light rounded">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
        <p><br></p>
    </div>
</div>
