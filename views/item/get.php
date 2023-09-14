<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Item $item */

$this->title = 'Item Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="item-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <td><?= Html::encode($item->id) ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= Html::encode($item->name) ?></td>
        </tr>
    </table>

    <?= Html::a('Changer de pneus', ['item/change'], ['class' => 'btn btn-primary',
        'data' => [
            'confirm' => ('Etes-vous sûr de vouloir changer de pneus ?')
        ],
    ]) ?>

</div>
