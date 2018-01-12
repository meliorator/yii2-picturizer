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
use yii\web\Controller;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

class Picturizer extends Widget {

    public $defaultImageUrl = '';
    public $previewImageUrl = '';
    public $minWidth = 256;
    public $minHeight = 256;
    public $pluginOptions = [];
    public $restrictText = '';

    /** @var bool Disable JCrop */
    public $withoutCrop = false;

    public $modelClass = 'meliorator\picturizer\PicturizerModel';

    /** @var  PicturizerModel */
    public $model;

    public function init() {
        $this->model = \Yii::createObject(['class' => $this->modelClass]);
        /** @var Controller | PicturizerControllerBehavior $controller */
        $controller = \Yii::$app->controller;
        $behaviors = $controller->getBehaviors();
        foreach ($behaviors as $behavior) {
            if($behavior instanceof PicturizerControllerBehavior){
                if(!$controller->isValidModel()){
                    $this->model->addErrors($controller->getErrorsValidation());
                }
            }
        }
        parent::init();
    }

    public function run(){
        $view = $this->getView();

        $assets = PicturizerAsset::register($view);

        if ($this->defaultImageUrl === '') {
            $this->defaultImageUrl = $assets->baseUrl . '/img/user.png';
        }

        if ($this->previewImageUrl === '') {
            $this->previewImageUrl = $this->defaultImageUrl;
        }

        if($this->withoutCrop){
            $this->pluginOptions['withoutCrop'] = true;
        }

        $options = Json::encode($this->pluginOptions);


        $js=<<<JS
jQuery("#{$this->getId()}").picturizer({$options}, {$this->minWidth}, {$this->minHeight});
JS;

        $view->registerJs($js, $view::POS_READY);

        return $this->render('picturizer', ['widget' => $this]);
    }
}