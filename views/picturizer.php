<?php
/** @var \app\components\View $this */
/** @var \meliorator\picturizer\Picturizer $widget */

use yii\helpers\Html;
// Html::img($widget->previewImageUrl, ['class' => 'preview img-responsive img-thumbnail']);
$model = new \meliorator\picturizer\PicturizerModel()
?>
<div id="<?= $widget->getId() ?>" class="crop-wrapper">

    <?= Html::activeHiddenInput($widget->model, 'widthImageFile', ['class' => 'width']) ?>
    <?= Html::activeHiddenInput($widget->model, 'heightImageFile', ['class' => 'height']) ?>
    <?= Html::activeHiddenInput($widget->model, 'topImageFile', ['class' => 'top']) ?>
    <?= Html::activeHiddenInput($widget->model, 'leftImageFile', ['class' => 'left']) ?>
    <?= Html::activeHiddenInput($widget->model, 'viewHeight', ['class' => 'viewHeight']) ?>
    <?= Html::activeHiddenInput($widget->model, 'viewWidth', ['class' => 'viewWidth']) ?>

    <div class="crop-preview">
        <?= Html::img($widget->previewImageUrl, ['class' => 'preview img-responsive']); ?>
    </div>
    <div class="crop-action">
        <?= Html::activeFileInput($widget->model, 'uploadImageFile'); ?>
    </div>
</div>

