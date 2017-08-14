<?php
/**
 * Created by Laba Mykola.
 * Date: 12.08.17
 * Time: 22:17
 */

namespace meliorator\picturizer;


use yii\web\AssetBundle;

class JCropAsset extends AssetBundle {

    public $sourcePath = '@bower/jcrop/';
    public $js = [
        'js/jquery.Jcrop.min.js'
    ];
    public $css = [
        'css/jquery.Jcrop.min.css'
    ];
}