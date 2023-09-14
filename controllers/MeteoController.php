<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Meteo;
use app\models\City;
use app\models\ContactForm;


class MeteoController extends Controller
{
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $cityId = $user->id_city;
        $userId = $user->id;

        $city = City::findOne($cityId);

        if ($city === null) {
            throw new \yii\web\NotFoundHttpException('Ville non trouvée.');
        }

        $lat = $city->lat;
        $lon = $city->lon;

        $meteo = new Meteo();
        $meteo->setPosition($lat, $lon);
        $data = $meteo->getData();

        $allMeteo = new Meteo();
        $allData = $allMeteo->getAllData();

        $moyenne = new Meteo();
        $moyenneTemperature = $moyenne->getAverageTemperature();

        $cityId = $moyenneTemperature['cityId'];
        $averageMin = $moyenneTemperature['averageMin'];
        $averageMax = $moyenneTemperature['averageMax'];

        $contactForm = new ContactForm();
        $contactForm->sendEmailIfNeeded($averageMin, $averageMax);
        // $contactForm->testMail();

        return $this->render('index', [
            'city' => $city,
            'data' => $data,
            'cityId' => $cityId,
            'averageMin' => $averageMin,
            'averageMax' => $averageMax,
        ]);
    }

}
