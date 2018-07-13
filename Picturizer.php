<?php
/**
 * Created by Laba Mykola.
 * Date: 12.08.17
 * Time: 2:14
 */

namespace meliorator\picturizer;


//use yii\base\Widget;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

class Picturizer extends Widget {

    public $defaultImageUrl = '';
    public $previewImageUrl = '';

    public $minWidth = 400;
    public $minHeight = 400;
    public $maxFileSize = 8 * 1024 * 1024;
    public $extensions = 'png, jpg, jpeg';

    public $pluginOptions = [];
    public $restrictText = '';

    public $previewMaxHeight;
    public $previewMaxWidth;

    /** @var bool Disable JCrop */
    public $withoutCrop = false;

    public $modelClass = 'meliorator\picturizer\PicturizerModel';

    /** @var  PicturizerModel */
    public $model;
    public $modelOptions = [];

    public function init() {
        $attributes = ArrayHelper::merge(
            ['class' => $this->modelClass],
            [
                'minWidth' => $this->minWidth, 'minHeight' => $this->minHeight,
                'maxFileSize' => $this->maxFileSize, 'extensions' => $this->extensions
            ],
            $this->modelOptions);
        $this->model = \Yii::createObject($attributes);
        /** @var Controller | PicturizerControllerBehavior $controller */
        $controller = \Yii::$app->controller;
        $behaviors = $controller->getBehaviors();
        foreach ($behaviors as $behavior) {
            if ($behavior instanceof PicturizerControllerBehavior) {
                if (!$controller->isValidModel()) {
                    $this->model->addErrors($controller->getErrorsValidation());
                }
            }
        }
        parent::init();
    }

    public function run() {
        $view = $this->getView();

        $assets = PicturizerAsset::register($view);

        if ($this->defaultImageUrl === '') {
            $this->defaultImageUrl = $assets->baseUrl . '/img/user.png';
        }

        if ($this->previewImageUrl === '') {
            $this->previewImageUrl = $this->defaultImageUrl;
        }

        if ($this->withoutCrop) {
            $this->pluginOptions['withoutCrop'] = true;
        }

        $jcropOptions = Json::encode($this->pluginOptions);

        $base = ['minWidth' => $this->minWidth, 'minHeight' => $this->minHeight];

        if ($this->previewMaxHeight) {
            $base['previewMaxHeight'] = $this->previewMaxHeight;
        }

        if ($this->previewMaxWidth) {
            $base['previewMaxWidth'] = $this->previewMaxWidth;
        }

        $commonOptions = Json::encode($base);

        $js
            = <<<JS
jQuery("#{$this->getId()}").picturizer({$jcropOptions}, {$commonOptions});
JS;

        $view->registerJs($js, $view::POS_READY);

        return $this->render('picturizer', ['widget' => $this]);
    }
}