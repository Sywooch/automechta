<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\behaviors\DateRangeSearchBehavior;
use common\models\AutoBody;
use common\models\AutoMakes;
use common\models\AutoModels;
use common\models\AutoModifications;
use common\models\AutoRegions;
use common\models\AutoModification;
use yii\base\Model;
use yii\db\Query;

class AutoSearch extends Model
{
    const SCENARIO_SEARCH = 'search';
    public $region;
    public $make;
    public $model;
    public $yearFrom;
    public $yearTo;

    public function init()
    {
        $this->setScenario(self::SCENARIO_SEARCH);
        parent::init();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = [
            'region',
            'make',
            'models',
            'yearFrom',
            'yearTo',
        ];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model' => Yii::t('app', 'Модель'),
            'make' => Yii::t('app', 'Марка'),
            'yearFrom' => Yii::t('app', 'Год выпуска от'),
            'yearTo' => Yii::t('app', 'Год выпуска до'),
            'region' => Yii::t('app', 'Регион'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'make', 'region'], 'required'],
            [['model', 'make', 'region', 'yearFrom', 'yearTo'], 'safe'],
        ];
    }

    /**
     * @param Query $query
     * @return Query
     */
    public function search()
    {
        $query = AutoModifications::find();

        if (!empty($this->region) && empty($this->make)) {
            $query->where(['region_id' => $this->region]);
        }

        if (!empty($this->make) && empty($this->model)) {
            $query->where(['make_id' => $this->make]);
        }

        if (!empty($this->model)) {
            $query->where(['model_id' => $this->model]);
        }
        if (!empty($this->yearFrom)) {
            $query->andWhere(['or', ['>=', 'auto_modifications.yearFrom', $this->yearFrom], ['or', ['auto_modifications.modification_name' => null]]]);
        }
        if (!empty($this->yearTo)) {
            $query->andWhere(['or', ['<=', 'auto_modifications.yearTo', $this->yearTo], ['or', ['auto_modifications.modification_name' => null]]]);
        }

        $query->orderBy('auto_modifications.yearFrom ASC');


        return $query;
    }

    /**
     * @return array
     */
    public static function getRegions()
    {
        $regions = array();
        foreach (AutoRegions::find()->all() as $region) {
            $regions[$region->id] = $region->region_name;
        }

        return $regions;
    }

    /**
     * @return array
     */
    public static function getMakes($regionId = 1)
    {
        $makes = array();
        foreach (AutoMakes::find()->where(['region_id' => $regionId])->all() as $make) {
            $makes[$make->id] = $make->name;
        }

        return $makes;
    }

    /**
     * @return array
     */
    public static function getMakesAll()
    {
        $makes = array();
        foreach (AutoMakes::find()->all() as $make) {
            $makes[$make->id] = $make->name;
        }

        return $makes;
    }


    /**
     * @return array
     */
    public static function getModels($makesId = 6)
    {
        $models = array();
        foreach (AutoModels::find()->where(['make_id' => $makesId])->all() as $model) {
            $models[$model->id] = $model->model;
        }

        return $models;
    }

    /**
     * @return array
     */
    public static function getModelsAll()
    {
        $models = array();
        foreach (AutoMakes::find()->all() as $model) {
            $models[$model->id] = $model->model;
        }

        return $models;
    }

    /**
     * @return array
     */
    public static function getYears()
    {
        $minYear = AutoModifications::find()->select('yearFrom')->where(['not', ['yearFrom' => null]])->orderBy(['yearFrom' => SORT_ASC])->one();
        $currentYear = date('Y');
        $years = array();

        for ($year = $minYear->yearFrom; $year <= $currentYear; $year++) {
            $years[$year] = $year;
        }

        return $years;
    }
}