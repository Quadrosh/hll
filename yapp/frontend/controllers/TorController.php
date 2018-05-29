<?php

namespace frontend\controllers;



use yii\filters\ContentNegotiator;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\web\Response;


class TorController extends \yii\web\Controller
{


    public function behaviors() {
        return [
            'contentNegotiator' => [
                'class'   => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],

        ];
    }

    public function beforeAction($action)
    {
        if (in_array($action->id, ['b2b','chepuha','motivator','local'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionLocal()
    {

        $input = Yii::$app->request->getRawBody();

        $url = isset(Yii::$app->params['localUrl'])?Yii::$app->params['localUrl']:null;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input);

        $r = curl_exec($ch);

        curl_close($ch);
        return $r;
    }

    public function actionChepuha()
    {
        $input = Yii::$app->request->getRawBody();

        $url=Yii::$app->params['chepuhaBotUrl'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Telebot');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input);

        $r = curl_exec($ch);

        curl_close($ch);
        return $r;
    }

    public function actionMotivator()
    {
        $input = Yii::$app->request->getRawBody();

        $url=Yii::$app->params['motivatorBotUrl'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Telebot');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input);

        $r = curl_exec($ch);

        curl_close($ch);
        return $r;
    }





    public function actionB2b()
    {

        $input = Yii::$app->request->getRawBody();


//        Yii::info([
//            'action'=>'request from Telega',
//            'input'=>Json::decode($input),
//        ], 'happyLifeBots');

        $url=Yii::$app->params['b2bBotUrl'];
        $body = $input;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Telebot');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $r = curl_exec($ch);
//        if($r == false){
//            $text = 'curl error '.curl_error($ch);
//            Yii::info($text, 'happyLifeBots');
//        } else {
//            $info = curl_getinfo($ch);
//            $info = [
//                    'action'=>'curl to RS',
//                    '$input'=>$input,
//                ] + $info;
//            Yii::info($info, 'happyLifeBots');
//        }
        curl_close($ch);
        return $r;
    }


}

