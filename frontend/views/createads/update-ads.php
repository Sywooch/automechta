<?php
use yii\widgets\ListView;
use common\models\Product;
use common\models\AppData;
use frontend\models\ProductSearchForm;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ProductMake;
use common\models\Specification;
use frontend\models\SpecificationForm;
use common\models\User;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Region;
use common\models\City;

/* @var $this yii\web\View */
/* @var $provider yii\data\ActiveDataProvider */
/* @var $productSpecifications common\models\ProductSpecification[] */
$tableView = filter_var(Yii::$app->request->get('tableView', 'false'), FILTER_VALIDATE_BOOLEAN);
$this->registerJs("require(['controllers/catalog/create']);", \yii\web\View::POS_HEAD);
$appData = AppData::getData();
$this->title = Yii::t('app', 'Редактировать объявление');
?>

<section class="b-pageHeader" style="background: url(<?= $appData['headerBackground']->getAbsoluteUrl() ?>) center;">
    <div class="container">
        <h1 class="wow zoomInLeft" data-wow-delay="0.5s">  Редактировать объявление</h1>
    </div>
</section><!--b-pageHeader-->

<?php
?>
<div class="b-breadCumbs s-shadow">
    <?= Breadcrumbs::widget([
        'links' => [
            $this->title
        ],
        'options' => ['class' => 'container wow zoomInUp', 'ata-wow-delay' => '0.5s'],
        'itemTemplate' => "<li class='b-breadCumbs__page'>{link}</li>\n",
        'activeItemTemplate' => "<li class='b-breadCumbs__page m-active'>{link}</li>\n",
    ]) ?>
</div><!--b-breadCumbs-->

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?= Alert::widget() ?>
        </div>
            <?php $formWidget = ActiveForm::begin([
                'id' => 'create_product_form',
                'action' =>'update-ads/save?id='.$model->id,
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['create-ads/validate']),
                'method' =>  'post',
                'options' => ['name' => 'update-ads','class' => 'create_ads update_ads clearfix'],
            ]); ?>
            <?php
            $stepParams = $_params_;
            $stepParams['formWidget'] = $formWidget;

        $productSpecificationsMainCols = [];
        $productSpecificationsAdditionalCols = [];
        $productSpecificationsMain = array_filter($productSpecifications, function ($productSpec) {
            $specification = $productSpec->getSpecification()->one();
            return $specification->type != Specification::TYPE_BOOLEAN;
        });
        $productSpecificationsMain = array_values($productSpecificationsMain);


        $productSpecificationsAdditional = array_filter($productSpecifications, function ($productSpec) {
            $specification = $productSpec->getSpecification()->one();
            return $specification->type == Specification::TYPE_BOOLEAN;
        });
        $productSpecificationsAdditional = array_values($productSpecificationsAdditional);

        $productSpecificationsAdditionalCols[0] = [];
        $productSpecificationsAdditionalCols[1] = [];

        foreach ($productSpecificationsMain as $key => $productSpecification) {
            $productSpecificationsMainCols[$key % 2][] = $productSpecification;
        }
        foreach ($productSpecificationsAdditional as $key => $productSpecification) {
            $productSpecificationsAdditionalCols[$key % 2][] = $productSpecification;
        }

        if (!isset($productSpecificationsMainCols[0])) {
            $productSpecificationsMainCols[0] = [];
        }
        if (!isset($productSpecificationsMainCols[1])) {
            $productSpecificationsMainCols[1] = [];
        }

        ?>
        <div class="col-lg-9 col-md-9 col-md-offset-1 col-lg-offset-1 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::activeHiddenInput($model, 'type') ?>
                    <?= Html::activeHiddenInput($form, 'step', ['value' => '3']) ?>
                </div>
                <div class="col-md-12">
                    <div class="wow zoomInUp field-user-last_name required" data-wow-delay="0.5s">
                        <label class="control-label" for="product-make"><?= $model->getAttributeLabel('make') ?> <span
                                class="text-danger">*</span></label>
                        <div class='s-relative-select'>
                            <?= Html::activeDropDownList(
                                $model,
                                'make',
                                ProductMake::getMakesList($model->type),
                                ['class' => 'm-select', 'prompt' => 'Выбрать', 'required' => true]) ?>
                            <div class="hint-block">
                                Нет нужной марки или модели?<br>Обратитесь в техподдержку и мы оперативно ее добавим
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="wow zoomInUp field-user-last_name required" data-wow-delay="0.5s">
                        <label class="control-label" for="product-model"><?= $model->getAttributeLabel('model') ?> <span
                                class="text-danger">*</span></label>
                        <div class='s-relative-select'>
                            <?= Html::activeDropDownList(
                                $model,
                                'model',
                                ProductMake::getModelsList($model->make),
                                ['class' => 'm-select', 'prompt' => 'Выбрать', 'required' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?= $formWidget->field($model->i18n(), "[" . Yii::$app->language . "]title", ['options' => ['class' => 'input wow zoomInUp', 'data-wow-delay' => '0.5s']])
                        ->textInput(['maxlength' => true, 'class' => '']) ?>
                </div>
                <div class="col-md-12 price-block">
                    <div class="first-block">
                        <?= $formWidget->field($model, "price", ['options' => ['class' => 'input  wow zoomInUp', 'data-wow-delay' => '0.5s', 'required' => true]])
                            ->textInput(['maxlength' => true, 'class' => '', 'required' => true])->label($model->getAttributeLabel('price') . ' <span class="text-danger">*</span>') ?>
                    </div>
                    <div class="two-block s-relative-select">
                        <?= Html::activeDropDownList(
                            $model,
                            'currency',
                            Product::getCurrencies(),
                            ['class' => 'm-select', 'required' => true]) ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="wow zoomInUp field-user-last_name required" data-wow-delay="0.5s">
                        <label class="control-label" for="product-year"><?= $model->getAttributeLabel('year') ?> <span
                                class="text-danger">*</span></label>
                        <div class='s-relative-select'>
                            <?= Html::activeDropDownList(
                                $model,
                                'year',
                                Product::getYearsList(),
                                ['class' => 'm-select', 'prompt' => 'Выбрать', 'required' => true]) ?>
                        </div>
                    </div>
                </div>

                <?php foreach ($productSpecificationsMainCols[0] as $productSpecification): ?>
                    <?php
                    $specification = $productSpecification->getSpecification()->one();
                    ?>
                    <div class="col-md-12">
                        <?= SpecificationForm::getControl($specification, $productSpecification, $formWidget) ?>
                    </div>
                <?php endforeach ?>
                <?php foreach ($productSpecificationsMainCols[1] as $productSpecification): ?>
                    <?php
                    $specification = $productSpecification->getSpecification()->one();
                    ?>
                    <div class="col-md-12">
                        <?= SpecificationForm::getControl($specification, $productSpecification, $formWidget) ?>
                    </div>
                <?php endforeach ?>
                <div class="col-md-12">
                    <?= $formWidget->field($model->i18n(), "[" . Yii::$app->language . "]seller_comments", ['options' => ['class' => ' wow zoomInUp', 'data-wow-delay' => '0.5s']])
                        ->textarea(['row' => 4, 'class' => '']) ?>
                </div>
                <header class="col-xs-12 s-headerSubmit s-lineDownLeft wow zoomInUp" data-wow-delay="0.5s">
                    <h4 class=""><?= Yii::t('app', 'Additional specifications') ?></h4>
                </header>
                <div class="col-md-12 main-element-checkbox">
                    <?php foreach ($productSpecificationsAdditionalCols[0] as $productSpecification): ?>
                        <?php
                        $specification = $productSpecification->getSpecification()->one();
                        ?>
                        <?= SpecificationForm::getControl($specification, $productSpecification, $formWidget) ?>
                    <?php endforeach ?>
                    <?php foreach ($productSpecificationsAdditionalCols[1] as $productSpecification): ?>
                        <?php
                        $specification = $productSpecification->getSpecification()->one();
                        ?>
                        <?= SpecificationForm::getControl($specification, $productSpecification, $formWidget) ?>
                    <?php endforeach ?>
                </div>
                <div class="col-md-12 col-xs-6 col-sm-7">
                    <div class="wow zoomInUp field-user-last_name" data-wow-delay="0.5s">
                        <label class="control-label" for="product-exchange"><?= $model->getAttributeLabel('exchange') ?> <span
                                class="text-danger">*</span></label>

                        <div id="product-exchange">

                            <input type="radio" name="Product[exchange]" value="0" id="product-exchange_yes"
                                <?php
                            if ($model->exchange == 0){echo 'checked';};
                            ?>
                            > <label
                                for="product-exchange_yes"><span>Нет</span></label>
                            <input type="radio" name="Product[exchange]" value="1" id="product-exchange_no"
                                <?php
                                if ($model->exchange == 1){echo 'checked';};
                                ?>
                            > <label
                                for="product-exchange_no"><span>Да</span></label>
                        </div>

                    </div>
                </div>
                <div class="col-md-12 col-xs-6 col-sm-7">
                    <div class="wow zoomInUp field-user-last_name" data-wow-delay="0.5s">
                        <label class="control-label" for="product-auction"><?= $model->getAttributeLabel('auction') ?> <span
                                class="text-danger">*</span></label>
                        <div id="product-exchange">
                            <input type="radio" name="Product[auction]" value="0" id="product-auction_yes"
                                <?php
                                if ($model->auction == 0){echo 'checked';};
                                ?>
                            > <label
                                for="product-auction_yes"><span>Нет</span></label>
                            <input type="radio" name="Product[auction]" value="1" id="product-auction_no"
                                <?php
                                if ($model->auction == 1){echo 'checked';};
                                ?>
                            > <label
                                for="product-auction_no"><span>Да</span></label>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <header class="col-xs-12 s-headerSubmit s-headerSubmit s-lineDownLeft wow zoomInUp" data-wow-delay="0.5s">
                    <h2 class="">Фотографии для объявления</h2>
                </header>
                <?= Html::activeHiddenInput($form, 'submitted', ['value' => '1']) ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
                <div class="mdl-textfield mdl-textfield--full-width mdl-textfield--floating-label is-dirty">
                    <div class="js-dropzone"
                         data-uploaded-files="<?= htmlspecialchars(json_encode(Yii::$app->uploads->getUploadsDataByModel($model)), ENT_QUOTES, 'UTF-8') ?>">
                        <div class="dz-default dz-message"><span class="upload btn m-btn">Выбрать фотографии</span> <span
                                class="drop">или перетащите изображения для загрзуки сюда</span></div>
                    </div>
                    <div class="hint-upload-photo">
                        Допускается загрузка не более 20 фотографий в формате JPG и PNG размером не более 8 МБ. <br>Фотография
                        помеченная
                        как "главная", будет отображенна первой. <br>Мы не рекомендуем Вам использовать фотошоп, рекламу и чужие
                        фотографии.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="hint-video"><h4> Видеоролик с вашей техникой</h4></div>
                    <?= $formWidget->field($model, "video", ['options' => ['class' => 'input wow zoomInUp', 'data-wow-delay' => '0.5s']])
                        ->textInput(['maxlength' => true, 'class' => '', 'required' => false])->label('Ссылка  видео на YouTube') ?>
                </div>
            </div>
            <div class="row">
                <header class="s-headerSubmit s-lineDownLeft wow zoomInUp" data-wow-delay="0.5s">
                    <h2 class=""><?= Yii::t('app', 'Contact details for ads') ?></h2>
                </header>
                <div class="row">
                    <div class="col-md-12">
                        <?= $formWidget->field($model, "first_name", ['options' => ['class' => 'input wow zoomInUp', 'data-wow-delay' => '0.5s']])
                            ->textInput(['maxlength' => true, 'class' => 'input ', 'required' => true])->label('Имя <span class="text-danger">*</span>') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="wow zoomInUp field-user-last_name required" data-wow-delay="0.5s">
                            <label class="control-label" for="user-phone">Область <span
                                    class="text-danger">*</span></label>
                            <div class='s-relative-select'>
                                <?= Html::activeDropDownList(
                                    $model,
                                    'region',
                                    Region::getRegions(),
                                    ['id' => 'region', 'class' => 'm-select', 'prompt' => 'Выбрать']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="wow zoomInUp field-user-last_name required" data-wow-delay="0.5s">
                            <label class="control-label" for="user-phone"><?= $model->getAttributeLabel('city_id') ?> <span
                                    class="text-danger">*</span></label>
                            <div class='s-relative-select'>
                                <?= Html::activeDropDownList(
                                    $model,
                                    'city_id',
                                    ArrayHelper::map(City::find()->where(['region' => $model->region])->asArray()->orderBy('city_name')->all(), 'id', 'city_name'),
                                    ['id' => 'city', 'class' => 'm-select', 'prompt' => 'Выберите область']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="contact-information-block col-md-12">
                        <div class="first-block">
                            <?= $formWidget->field($model, "phone", ['options' => ['class' => 'input b-submit__main-element wow zoomInUp', 'data-wow-delay' => '0.5s']])
                                ->textInput(['maxlength' => true, 'class' => 'input', 'required' => true])->label($model->getAttributeLabel('phone') . '<span class="text-danger">*</span>') ?>
                        </div>
                        <div class="two-block">
                            <div class='s-relative-select'>
                                <?= Html::activeDropDownList(
                                    $model,
                                    'phone_provider',
                                    User::getPhoneProviders(),
                                    ['class' => 'm-select']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="contact-information-block col-md-12">
                        <div class="first-block">
                            <?= $formWidget->field($model, "phone_2", ['options' => ['class' => 'input b-submit__main-element wow zoomInUp', 'data-wow-delay' => '0.5s']])
                                ->textInput(['maxlength' => true, 'class' => 'input', 'required' => false])->label($model->getAttributeLabel('phone_2')) ?>
                        </div>
                        <div class="two-block">
                            <div class='s-relative-select'>
                                <?= Html::activeDropDownList(
                                    $model,
                                    'phone_provider_2',
                                    User::getPhoneProviders(),
                                    ['class' => 'm-select']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <button type="submit" id="button_publish" class="btn m-btn pull-right wow zoomInUp" data-wow-delay="0.5s">ОПУБЛИКОВАТЬ<span class="fa fa-angle-right"></span></button>
            <?php ActiveForm::end(); ?>
    </div>
</div>