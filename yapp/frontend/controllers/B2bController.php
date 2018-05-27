<?php

namespace frontend\controllers;



use yii\filters\ContentNegotiator;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\web\Response;


class B2bBotController extends \yii\web\Controller
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
        if (in_array($action->id, ['do','test'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


    /*
     * Основной метод, принимает запросы от пользователя.
     *
     * @return array Массив с кодом 200 (индикация успешной обработки запроса)
     *    ['message' => 'ok', 'code' => 200]
     * */
    public function actionDo()
    {

        $input = Yii::$app->request->getRawBody();
        $updateId = Yii::$app->request->post('update_id');
        $message = Yii::$app->request->post('message'); // array
        $callbackQuery = Yii::$app->request->post('callback_query'); // array
        $inlineQuery = Yii::$app->request->post('inline_query'); // array



        Yii::info([
            'action'=>'request from Telega',
            'input'=>Json::decode($input),
        ], 'happyLifeBots');

        return 'ok';



    }



    private function curlCall($url, $option=array(), $headers=array())
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Telebot");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (count($option)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $option);
        }
        $r = curl_exec($ch);
        if($r == false){
            $text = 'error '.curl_error($ch);
            $myfile = fopen("error_telegram.log", "w") or die("Unable to open file!");
            fwrite($myfile, $text);
            fclose($myfile);
        }
        curl_close($ch);
        return $r;
    }

}

