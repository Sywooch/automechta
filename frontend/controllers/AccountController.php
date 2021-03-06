<?php

namespace frontend\controllers;

use common\models\ProductSearch;
use common\models\User;
use common\models\Product;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\Uploads;
use yii\web\HttpException;

/**
 * Account controller
 */
class AccountController extends Controller
{
    public $layout = 'index';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'move-node' => ['post'],
                    'delete-product' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        $model = User::findOne(Yii::$app->user->id);
        $model->setScenario('sellerContacts');

        $query = Product::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->where(['created_by' => Yii::$app->user->id]);
        $query->andWhere(['!=', 'status', Product::STATUS_UNPUBLISHED]);
        $query->orderBy('product.updated_at DESC');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Saved'));
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return array|Response
     */
    public function actionUpProduct($id)
    {
        $model = Product::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Yii::$app->user->can('updateOwnProduct', ['model' => $model])) {
            Yii::$app->user->denyAccess();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $time = time() - (1 * 24 * 60 * 60);
        if ($model->updated_at < $time) {
            $model->updated_at = $time;
            if ($model->save()) {
                Yii::$app->cache->deleteKey('main_page');

                return ['status' => 'success', 'id' => $id];
            } else {
                return ['status' => 'failed', 'id' => $id];
            }
        } else {
            return ['status' => 'failed', 'id' => $id];
        }
    }

    /**
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteProduct($id)
    {
        $model = Product::find()->where(['id'=>$id])->one();

        if ($model->id === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Yii::$app->user->can('deleteOwnProduct', ['model' => $model])) {
            Yii::$app->user->denyAccess();
        }

        $model->status = Product::STATUS_UNPUBLISHED;
        $model->save();
        Yii::$app->cache->deleteKey('main_page');

        try {
            Uploads::deleteImages('product', $id);
        }
        catch (HttpException $e){

        }
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => 'success', 'id' => $id];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Validate user data
     * @return array
     */
    public function actionValidate()
    {
        $model = User::findOne(Yii::$app->user->id);

        if ($model === null) {
            $model = new User();
        }
        $model->setScenario('sellerContacts');
        $model->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
}
