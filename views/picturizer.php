<?php
/** @var \app\components\View $this */
/** @var \meliorator\picturizer\Picturizer $widget */

use yii\helpers\Html;

?>

<?= Html::hiddenInput('width', $widget->width); ?>
<?= Html::hiddenInput('height', $widget->height); ?>

<div class="preview-wrapper">
    <?= Html::img($widget->previewImageUrl, ['class' => 'preview']); ?>
</div>
