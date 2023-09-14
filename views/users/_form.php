<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
$user = Yii::$app->user->identity;
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?> -->
    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <!-- <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'id_type_user')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\TypeUser::find()->all(), 'id', 'name'),
        ['prompt' => 'Sélectionner le type d\'utilisateur']
    ) ?> -->

    <?= $form->field($model, 'id_city')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\City::find()->all(), 'id', 'name'),
        ['prompt' => 'Sélectionner une ville']
    ) ?>

    <?= $form->field($model, 'id_habits')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Habits::find()->all(), 'id', 'name'),
        ['prompt' => 'Sélectionner les habitudes de roulage']
    ) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'terms')->checkbox(['label' => 'J\'accepte la politique de confidentialité et les ' . Html::a('conditions générales', ['politic'])]) ?>
    <?php endif; ?>

    <?php if (!$model->isNewRecord && $user->isAdmin()): ?>
        <?= $form->field($model, 'isMailSend')->checkbox() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
