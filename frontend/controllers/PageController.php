<?php

namespace frontend\controllers;

use yii\data\ActiveDataProvider;
use Yii;
use yii\web\Controller;
use common\models\Page;
use common\helpers\Url;

/**
 * Page controller
 */
class PageController extends Controller
{
    public $layout = 'index';
    public $bodyClass;

    public function beforeAction($action)
    {
        Url::remember('/account/index', 'previous');

        return parent::beforeAction($action);
    }

    /**
     * @param string $alias page alias
     *
     * @return index
     */
    public function actionShow($alias)
    {
        $model = $this->findModel($alias);

        $model->increaseViews();
        if ($alias == 'avto-v-kredit') {
            return $this->render('avto-v-kredit', [
                'model' => $model,
            ]);
        } else {
            return $this->render('show', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Finds the Product model based on its primary key value (id).
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alias)
    {
        if (($model = Page::find()->where(['alias' => $alias])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
