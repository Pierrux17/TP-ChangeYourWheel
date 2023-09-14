<?php

use yii\helpers\Html;

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

/** @var yii\web\View $this */
/** @var app\models\Users $model */


$this->title = 'Update Users: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-update">

<div class="container bg-light rounded">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= Html::a('Modifier le mot de passe', ['change-password'],  ['class' => 'btn btn-primary']) ?>
    <p><br></p>

</div>


</div>
