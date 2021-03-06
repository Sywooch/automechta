<?php
namespace frontend\controllers;

use yii\data\ActiveDataProvider;
use Yii;
use yii\web\Controller;
use common\models\Page;
use common\helpers\Url;

/**
 * News controller
 */
class NewsController extends Controller
{
    public $layout = 'index';
    public $bodyClass;
    const PAGE_SIZE = 5;

    public function beforeAction($action)
    {
        Url::remember('/account/index', 'previous');

        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Page::find()->andWhere(['not', ['main_image' => null]])->active()->news()->orderBy('id desc');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => static::PAGE_SIZE,
            ],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'robots',
            'content' => 'noindex, nofollow'
        ]);

        return $this->render('index', [
            'provider' => $provider
        ]);
    }

    /**
     * @param integer $id product id
     *
     * @return index
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);

        $model->increaseViews();
        Yii::$app->view->registerMetaTag([
            'name' => 'robots',
            'content' => 'noindex, nofollow'
        ]);

        return $this->render('show', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Product model based on its primary key value (id).
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
