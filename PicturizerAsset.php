<?php
/**
 * Created by Laba Mykola.
 * Date: 12.08.17
 * Time: 18:02
 */

namespace meliorator\picturizer;


use yii\web\AssetBundle;

class PicturizerAsset extends AssetBundle {

    public $sourcePath = '@vendor/meliorator/yii2-picturizer/assets';

    public $css = [
        'css/picturizer.css'
    ];

    public $js = [
        'js/picturizer.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'meliorator\picturizer\JCropAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];

}