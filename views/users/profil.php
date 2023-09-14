<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Mon profil';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-profile">

    <div class="bg-light rounded text-center">
        <p><br></p>
        <h1><?= Html::encode($this->title) ?></h1>
        <p><br></p>
        
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <p><strong>Nom :</strong> <?= Html::encode($model->lastname) ?></p>
                <p><strong>Prénom :</strong> <?= Html::encode($model->firstname) ?></p>
                <p><strong>Mail :</strong> <?= Html::encode($model->mail) ?></p>
                <p><strong>Ville :</strong> <?= Html::encode($model->getCityName()) ?></p>
                <p><strong>Habitudes de roulage :</strong> <?= Html::encode($model->getHabitsName()) ?></p>
            </div>
        </div>
        <p><br><br></p>

        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <?= Html::a('Modifier mon compte', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Supprimer mon compte', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            </div>
        </div>
        <p><br><br></p>
    </div>

</div>
