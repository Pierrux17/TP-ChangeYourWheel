<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

use DateTime;
use DateTimeZone;
use PhpMimeMailParser;
use PhpMimeMailParser\Parser;


/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $scenario = 'default';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $rules = [
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            // ['verifyCode', 'captcha'],
        ];

        if ($this->scenario === 'contact') {
            $rules[] = [['subject', 'body'], 'required'];
        }

        return $rules;
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Envoie un email en spécifiant la sujet et le corps du mail
     */
    public function contact()
    {
        $admin = Users::findOne(['id_type_user' => 1]);
        $user = Yii::$app->user->identity;

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($admin->mail)
                ->setFrom($user->mail)
                // ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->setCharset('UTF-8')
                ->setDate($date)
                ->send();

            return true;
        }

        return false;
    }

    /**
     * Envoie un mail si les conditions sont réunies
     * 
     * @param $averageMin
     * @param $averageMax
     */
    public function sendEmailIfNeeded($averageMin, $averageMax)
    {
        if ($this->validate()) {
            $sendEmail = false;
            $user = Yii::$app->user->identity;
            $habits = $user->id_habits;
            $username = $user->lastname;
            $id_city = $user->id_city;
            $city = City::findOne($id_city);
            $admin = Users::findOne(['id_type_user' => 1]);

            $date = new DateTime('now', new DateTimeZone('Europe/Paris'));

            $this->body = "";

            //Moyenne des températures de nuit + de 7°C et que l'utilisateur roule la nuit
            if ($averageMin > 7 && $habits == 2) {
                $this->body .= "Madame Monsieur " . $username . "\n\n" . "La temperature moyenne de nuit a " . $city->name .  " est superieure a 7 degres Celsius. Il est recommande de passer aux pneus ete.";
                $sendEmail = true;
            }

            //Moyenne des températures + de 7°C, roulage de jour
            if ($averageMax > 7 && $habits == 1) {
                $this->body .= "Madame Monsieur " . $username . "\n\n" . "La temperature moyenne de jour a " . $city->name .  " est supérieure a 7 degres Celsius. Il est recommande de passer aux pneus ete.";
                $sendEmail = true;
            }

            //Moyenne des températures - de 7°C, roulage de nuit
            if ($averageMin < 7 && $habits == 2) {
                $this->body .= "Madame Monsieur " . $username . "\n\n" . "La temperature moyenne de nuit a " . $city->name .  " est inferieure a 7 degrés Celsius. Il est recommande de passer aux pneus hiver.";
                $sendEmail = true;
            }

            //Moyenne des températures - de 7°C, roulage de jour
            if ($averageMax < 7 && $habits == 1) {
                $this->body .= "Madame Monsieur " . $username . "\n\n" . "La temperature moyenne de jour a " . $city->name .  " est inférieure a 7 degres Celsius. Il est recommande de passer aux pneus hiver.";
                $sendEmail = true;
            }

            $this->body = quoted_printable_encode($this->body);

            if($averageMin == 0 && $averageMax == 0){
                $sendEmail = false;
            }

            if ($sendEmail) {
                // Vérifie si le user veut recevoir des mails
                if ($user->isMailSend) {
                    // Vérifie si le mail a déjà été envoyé aujourd'hui à ce user
                    $cacheKey = 'email_sent_' . $user->id;
                    $lastSentDate = Yii::$app->cache->get($cacheKey);
        
                    if ($lastSentDate === false || $lastSentDate < $date->format('Y-m-d')) {
                        // Le mail n'a pas encore été envoyé aujourd'hui, donc on l'envoie maintenant
                        if ($user !== null) {
                            Yii::$app->mailer->compose()
                                ->setTo($user->mail)
                                ->setFrom($admin->mail)
                                ->setSubject("Changement de pneus")
                                ->setTextBody($this->body)
                                ->setDate($date)
                                ->send();
        
                            Yii::$app->cache->set($cacheKey, $date->format('Y-m-d'));
        
                            return true;
                        }
                    }
                }
            }    

        }

        return false;
    }
}
