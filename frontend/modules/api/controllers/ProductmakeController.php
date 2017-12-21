<?php

namespace frontend\modules\api\controllers;

use common\models\ProductMake;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\controllers\TreeController;
use yii\db\Query;
use common\models\Product;
use frontend\models\ProductSearchForm;

class ProductmakeController extends TreeController
{
    public $modelName = 'common\models\ProductMake';

    /**
     * Get list of makers by product type
     * @param $type
     * @return mixed
     */



    public function actionMakers($type)
    {
        if (!Yii::$app->user->can('viewProductMake')) {
            Yii::$app->user->denyAccess();
        };

        Yii::$app->response->format = Response::FORMAT_JSON;

        $cache = Yii::$app->cache;

        $key   = $type;

        $data  = $cache->get($key);
        if (($data === null) || ($data === false)) {
            $key  = $type;
            $data = (new Query())->select('name, id')
                ->from('product_make')
                ->where('product_type=:product_type AND depth=1', [':product_type' => $type])
                ->indexBy('id')->column();
            $cache->set($key, $data);

            return $data;
        }
        else {
         return $data;
        }
    }

    /**
     * Get list of models by make id
     * @param $makeId
     * @return mixed
     */
    public function actionModels($makeId)
    {
        if (!Yii::$app->user->can('viewProductMake')) {
            Yii::$app->user->denyAccess();
        };

        Yii::$app->response->format = Response::FORMAT_JSON;

        $cache = Yii::$app->cache;
        $key   = $makeId;
        $data  = $cache->get($key);
        if ($data === false) {
            $key  = $makeId;
            $model = $this->findModel($makeId);
            $result = ProductMake::getModelsList($model->id);
            $data = $result;
            $cache->set($key, $data);

            return $data;
        }
        else {
            return $data;
        }
    }

    public function actionSearch()
    {
        if (Yii::$app->request->isAjax) {
            $params = Yii::$app->request->get();

            $searchForm = new ProductSearchForm();
            $query = Product::find()->active();
            $searchForm->load($params);
            if (!empty($params['ProductSearchForm']['specs'])) {
                $searchForm->specifications = $params['ProductSearchForm']['specs'];
            }
            $total = $searchForm->search($query)->count();

            return $total;
        }
    }

    /**
     * Finds the ProductMake model based on its primary key value (id).
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $id = $this->getNodeId($id);
        if (($model = ProductMake::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}