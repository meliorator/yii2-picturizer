<?php
/** @var \app\components\View $this */

/** @var \meliorator\picturizer\Picturizer $widget */

use yii\helpers\Html;

$errors = '';
if ($widget->model->hasErrors()) {
    foreach ($widget->model->getErrors() as $error) {
        $errors .= implode($error);
    }
}

$previewCss = ['class' => 'preview'];
$style = '';
if($widget->previewMaxHeight){
    $style = 'max-height: ' . $widget->previewMaxHeight . 'px;';
}

if($widget->previewMaxWidth){
    $style .= 'max-width: ' . $widget->previewMaxWidth . 'px;';
}
if($style){
    $previewCss['style'] = $style;
}
?>
<div id="<?= $widget->getId() ?>" class="crop-wrapper">

    <?php if (!$widget->withoutCrop): ?>
        <?= Html::activeHiddenInput($widget->model, 'widthImageFile', ['class' => 'width']) ?>
        <?= Html::activeHiddenInput($widget->model, 'heightImageFile', ['class' => 'height']) ?>
        <?= Html::activeHiddenInput($widget->model, 'topImageFile', ['class' => 'top']) ?>
        <?= Html::activeHiddenInput($widget->model, 'leftImageFile', ['class' => 'left']) ?>
        <?= Html::activeHiddenInput($widget->model, 'viewHeight', ['class' => 'viewHeight']) ?>
        <?= Html::activeHiddenInput($widget->model, 'viewWidth', ['class' => 'viewWidth']) ?>
    <?php endif; ?>

    <div class="crop-preview">
        <?= Html::img($widget->previewImageUrl, $previewCss); ?>
    </div>

    <?php if ($errors): ?>
        <p class="text-danger"><?= $errors; ?></p>
    <?php endif ?>

    <?= $widget->restrictText; ?>

    <div class="crop-action">
        <div tabindex="500" class="btn btn-primary btn-block btn-file">
            <i class="glyphicon glyphicon-camera"></i>   <span class="hidden-xs">Select Photo</span>
            <?= Html::activeFileInput($widget->model, 'uploadImageFile'); ?>
        </div>
    </div>
</div>

