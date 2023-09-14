<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\HttpException;

use yii\caching\FileCache;

class Meteo
{
    public $lat;
    public $lon;

    public function __construct($lat = null, $lon = null, $config = [])
    {
        $this->lat = 0.0;
        $this->lon = 0.0;
    }

    /**
     * Défini les positions de la ville où doit être calculée la météo
     * 
     * @param $lat
     * @param $lon
     */
    public function setPosition($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * Récupère les températures moyenne par jour pour une ville
     */
    public function getData()
    {
        $apiToken = "e7bfb2aefc6ef376136e1e91f22bf79af830b81bb5fb8a1526507f56fa3157ac";

        $apiUrl = "https://api.meteo-concept.com/api/forecast/daily/0/periods?token=" . $apiToken . "&latlng=" . $this->lat . "," . $this->lon . "&world=true";

        $response = file_get_contents($apiUrl);

        if ($response === false) {
            throw new HttpException(500, 'Impossible de récupérer les données météorologiques.');
        }

        $data = Json::decode($response, true);

        //Verifie nom de la ville, la latitude et longitude;
        $name = $data['city']['name'];
        $latitude = round($data['city']['latitude'], 2);
        $longitude = round($data['city']['longitude'], 2);

        //INIT
        $forecastData = $data['forecast'];
        $hourlyData = [];

        $temperatureMin = 0;
        $temperatureMax = 0;

        foreach ($forecastData as $forecast) {
            $datetime = $forecast['datetime'];
            $temperature = round($forecast['temp2m'], 2);
        
            if ($forecast['period'] == 0 || $forecast['period'] == 1) {
                $temperatureMin += $temperature;
            } elseif ($forecast['period'] == 2 || $forecast['period'] == 3) {
                $temperatureMax += $temperature;
            }
        
            $hourlyData[] = [
                'time' => $datetime,
                'temperature' => $temperature,
            ];
        }
        
        
        //CALCUL MOYENNES
        $temperatureMin = $temperatureMin / 2;
        $temperatureMax = $temperatureMax / 2;

        //DATE
        $date = date('Y-m-d', strtotime($forecastData[0]['datetime']));

        return [
            'name' => $name,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'hourly' => $hourlyData,
            'temperatureMin' => $temperatureMin,
            'temperatureMax' => $temperatureMax,
            'date' => $date,
        ];
    }


    /**
     * Récupère les données pour toutes les villes et les envoie dans la db
     */
    public function getAllData()
    {
        $users = Users::find()->all();

        // Tableau pour récupérer les villes doublons, ne calcule qu'une seule fois pour la ville associée à l'utilisateur + tableau de data
        $CityDoublons = [];
        $allData = [];

         // Créer un cache
         $cache = new FileCache();

        foreach ($users as $user) {
            $city = $user->city;
            $lat = $city->lat;
            $lon = $city->lon;

            // Vérifie si la ville a déjà été traitée
            if (in_array($city->id, $CityDoublons)) {
                continue;
            }

            // Clé unique pour stocker les données météo de la ville pour la journée
            $cacheKey = 'city_data_' . $city->id . '_' . date('Y-m-d');

            // Vérifier si les données de la ville sont déjà en cache
            $data = $cache->get($cacheKey);

            if ($data === false) {
                // Les données ne sont pas en cache, effectuer le calcul

                // Enregistrer la position de la ville et calculer les données
                $meteo = new Meteo();
                $meteo->setPosition($lat, $lon);
                $data = $meteo->getData();

                // Enregistrer les données dans la DB
                $newData = new Data();
                $newData->value_min = $data['temperatureMin'];
                $newData->value_max = $data['temperatureMax'];
                $newData->datetime = $data['date'];
                $newData->id_city = $city->id; // Utiliser l'ID de la ville courante
                $newData->save();

                // Stocker les données en cache jusqu'à minuit de demain
                $cache->set($cacheKey, $data, strtotime('tomorrow') - time());
            } 

            $allData[] = [
                'city' => $city,
                'data' => $data,
            ];

            $CityDoublons[] = $city->id;
        }

        // $cache->flush(); --> delete complètement cache si besoin
        return $allData;
    }
    

    /**
     * Retourne les moyennes des températures pour chaque jour
     */
    public function getAverageTemperature()
    {
        $fourDaysAgo = strtotime('-4 days');

        $user = Yii::$app->user->identity;
        $cityId = $user->id_city;

        $dataTemp = Data::find()
            ->where(['id_city' => $cityId])
            ->andWhere(['>=', 'datetime', date('Y-m-d H:i:s', $fourDaysAgo)])
            ->all();

        $totalMin = 0;
        $totalMax = 0;
        $count = 0;

        foreach ($dataTemp as $data) {
            $totalMin += $data->value_min;
            $totalMax += $data->value_max;
            $count++;
        }

        if ($count >= 4) {
            $averageMin = $totalMin / $count;
            $averageMax = $totalMax / $count;
        } else {
            $averageMin = 0;
            $averageMax = 0;
        }

        return [
            'cityId' => $cityId,
            'averageMin' => $averageMin,
            'averageMax' => $averageMax,
        ];
    }

}
