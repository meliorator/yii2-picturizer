<?php
/**
 * Created by Laba Mykola.
 * Date: 12.08.17
 * Time: 2:14
 */

namespace meliorator\picturizer;


//use yii\base\Widget;
use yii\widgets\InputWidget;

class Picturizer extends InputWidget {

    public $defaultImageUrl = '';
    public $previewImageUrl = '';
    public $width = 200;
    public $height = 200;

    public function run(){
        $view = $this->getView();

        $assets = PicturizerAsset::register($view);

        if ($this->defaultImageUrl === '') {
            $this->defaultImageUrl = $assets->baseUrl . '/img/user.svg';
        }

        if ($this->previewImageUrl === '') {
            $this->previewImageUrl = $this->defaultImageUrl;
        }

        $js=<<<JS
jQuery("#{$this->options['id']}").parent().find(".new-photo-area").cropper();
JS;

        $view->registerJs($js, $view::POS_READY);

        return $this->render('picturizer', ['widget' => $this]);
    }
}