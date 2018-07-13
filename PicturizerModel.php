<?php
/**
 * Created by Laba Mykola.
 * Date: 14.08.17
 * Time: 21:10
 */

namespace meliorator\picturizer;


use app\components\Utils;
use yii\base\Model;
use meliorator\helpers\FileHelper;

class PicturizerModel extends Model {

    /** @var  UploadedFile */
    public $uploadImageFile;

    /** @var  int */
    public $widthImageFile;

    /** @var  int */
    public $heightImageFile;

    /** @var  int */
    public $topImageFile;

    /** @var  int */
    public $leftImageFile;

    /** @var  int */
    public $viewHeight;

    /** @var  int */
    public $viewWidth;

    public $required = false;

    public $extensions = 'png, jpg, jpeg';

    public $maxFileSize = 8*1024*1024;

    public $minWidth  = 400;
    public $maxWidth = 2000;

    public $minHeight = 400;
    public $maxHeight = 2000;

    public function rules() {
        $rules = [
            [['widthImageFile', 'heightImageFile', 'topImageFile', 'leftImageFile', 'viewHeight', 'viewWidth'], 'integer'],
            [
                ['uploadImageFile'], 'image', 'extensions' => $this->extensions, 'maxSize' => $this->maxFileSize,
                'minWidth' => $this->minWidth, 'maxWidth' => $this->maxWidth,
                'minHeight' => $this->minHeight, 'maxHeight' => $this->maxHeight,
            ],

        ];
        if($this->required){
            $rules[] = [['uploadImageFile'], 'required'];
        }
        return $rules;
    }

    /**
     * @param PicturizerModelBehavior $model
     *
     * @return bool
     */
    public function uploadImageTo($savePath) {
        $errorMessage = 'Upload filed';
        $isCreated = FileHelper::createDirectory($savePath);
        if ($isCreated && $this->uploadImageFile) {
            $fileName = FileHelper::getFileNameUnique($savePath, $this->uploadImageFile->baseName, $this->uploadImageFile->extension);
            if ($this->uploadImageFile->saveAs($savePath . '/' . $fileName)) {
                return [true, $fileName];
            }
        } else {
            $errorMessage = 'Failed created directory ' . $savePath;
        }
        return [false, $errorMessage];
    }

    public function getRealCropParameters($imageH, $imageW) {
        $realH = round((($this->heightImageFile * $imageH) / $this->viewHeight), 0);
        $realW = round((($this->widthImageFile * $imageW) / $this->viewWidth), 0);
        $realX = round((($this->leftImageFile * $imageW) / $this->viewWidth), 0);
        $realY = round((($this->topImageFile * $imageH) / $this->viewHeight), 0);

        return [$realH, $realW, $realX, $realY];
    }
}