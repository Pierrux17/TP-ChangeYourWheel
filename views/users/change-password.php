<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

/** @var yii\web\View $this */
/** @var app\models\ChangePasswordForm $model */

$this->title = 'Modifier le mot de passe';
$this->params['breadcrumbs'][] = ['label' => 'Profil', 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container bg-light rounded">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'currentPassword')->passwordInput() ?>
    <?= $form->field($model, 'newPassword')->passwordInput() ?>
    <?= $form->field($model, 'confirmPassword')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Modifier', ['class' => 'btn btn-primary']) ?>
    </div>
    <p><br></p>
    <?php ActiveForm::end(); ?>
</div>
