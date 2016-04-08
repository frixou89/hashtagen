<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'read-url' => ['post'], //Allow only post requests for actionReadUrl. (Yii2 splits CamelCase actions with '-')
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Renders the index page
     * @return VIEW
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Reads a url and return it's contents
     * @param string $url
     * @return URL Data
     * @throws NotFoundHttpException if the url cannot be found
     */
    public function actionReadUrl()
    {   
        //Get url from post request
        $url = Yii::$app->request->post('url'); 
        //Check if a url parameter was retrieved by the POST request.
        //Throws an error if not set or empty
        if (!$url) {
            throw new NotFoundHttpException('URL is missing!');
        }
        //Create a dynamic model. No need for a Class
        $model = new \yii\base\DynamicModel(['TAG']);
        $model->addRule(['TAG'], 'url');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //Get the page content
            $page = file_get_contents($url);

            //Force the controller to return a JSON format encode.
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $page; 
        } else {
            return $model;
        }

    }

}