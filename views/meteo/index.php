<?php
use yii\helpers\Html;

use app\assets\AppAsset;
$this->registerAssetBundle('app\assets\StyleAsset');

$this->title = 'Météo - ' . $city->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-light rounded text-center">
    <div class="card bg-light">
        <h1>Météo</h1>
        <div class="card-body">
            <h3><?= Html::encode($city->name) ?></h3>

            <p class="mb-0">Date : <?= Html::encode($data['date']) ?></p>

            <h5 class="mt-4">Températures :</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    Moyenne température de nuit : <?= Html::encode($data['temperatureMin']) ?> °C
                </li>
                <li class="list-group-item">
                    Moyenne température de jour : <?= Html::encode($data['temperatureMax']) ?> °C
                </li>
            </ul>

            <p><br></p>
            <h5 class="mt-4">Températures moyenne des 4 derniers jours :</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    Moyenne température de nuit SUR 4 JOURS : <?= Html::encode($averageMin) ?> °C
                </li>
                <li class="list-group-item">
                    Moyenne température de jour SUR 4 JOURS : <?= Html::encode($averageMax) ?> °C
                </li>
            </ul>
        </div>
    </div>
</div>
