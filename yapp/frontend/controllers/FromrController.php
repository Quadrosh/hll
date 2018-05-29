<?php

namespace frontend\controllers;



use common\models\Request;
use yii\filters\ContentNegotiator;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\web\Response;


class FromrController extends \yii\web\Controller
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
        if (in_array($action->id, ['tot'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionTot()
    {

        $input = Yii::$app->request->getRawBody();

        $rawUrl=$_SERVER['REQUEST_URI'];

        $strPos = strpos($rawUrl,'?tourl=');
        $substr = substr($rawUrl,$strPos+7);

        $url = str_replace(Yii::$app->params['patch'],Yii::$app->params['t'],$substr);

//        Yii::info([
//            'action'=>'tot',
//            'Json::decode($input)'=>Json::decode($input),
//            '$url'=>$url,
//        ], 'happyLifeBots');
//        return $url;

        $request = new Request();
        $request['text'] = strval($url);
        $request->save();



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



}

