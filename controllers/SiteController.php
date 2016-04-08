<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\Tag;
use Embed\Embed;
use yii\data\ArrayDataProvider;
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
        //Create new Tag object
        $model = new Tag;

        //Render index.php and send $model to the VIEW
        return $this->render('index', [
                'model' => $model,
            ]);
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

        //Force the controller to return a JSON format encode.
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Tag();
        $model->url = $url;

        if ($model->validate()) {
        
        $info = Embed::create($model->url);
        if (!$info) {
            return new NotFoundHttpException('Bad or invalid');
        }

        $model->title = $info->title;
        $model->content = $info->getRequest()->getContent();
        $model->content = $model->cleanContent;
        $model->description = $info->description;

        $tags = implode(', ', $info->tags);
        $array1 = explode(' ', $info->title . ', ' . $info->description);
        $array2 = explode(' ', $model->content);
        $defaults =  $tags . ', ' . $info->authorName;

        // $model->words = $model->compare($array1, $array2, $defaults);
        $model->words = $model->hashtags($array1, $array2, $defaults);

        return $this->renderPartial('table', [
                'model' => $model,
            ]);
        } else {
            return $model;
        }
    }

}
