<?php
/**
 * Created by Laba Mykola.
 * Date: 13.08.17
 * Time: 21:52
 */

namespace meliorator\picturizer;


use app\components\Utils;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class PicturizerControllerBehavior extends Behavior {

    public $savePath;

    public $modelClass = 'meliorator\picturizer\PicturizerModel';

    /** @var PicturizerModel */
    private $model;

    private $_valid = false;

    /**
     * @return bool
     */
    public function isValidModel() {
        return $this->_valid;
    }

    private $_errorsValidation = [];

    /**
     * @return array
     */
    public function getErrorsValidation() {
        return $this->_errorsValidation;
    }

    public function init() {
        if (!$this->savePath) {
            throw new CommonException('Property savePath is not defined.');
        }
        parent::init();
    }

    public function validateFile(){
        if(!$this->model){
            $this->initModel();
        }

        $this->_valid = $this->model->validate();
        $this->_errorsValidation = $this->model->getErrors();
        return $this->isValidModel();
    }

    public function saveUploadedImage($operation = []) {
        if (\Yii::$app->request->isPost) {

            if(!$this->model){
                $this->initModel();
            }

            $replacePath = ArrayHelper::getValue($operation, 'replace', '');
            if($replacePath){
                $this->savePath = strtr($this->savePath, $replacePath);
            }

            list($success, $fileName) = $this->model->uploadImageTo($this->getImagePath());
            if ($success) {
                $filePath = $this->getImagePath() . '/' . $fileName;

                $op = ArrayHelper::getValue($operation, 'op', 'crop');
                if($op === 'crop'){
                    $imagine = new \Imagine\Imagick\Imagine();
                    $image = $imagine->open($filePath);
                    $size = $image->getSize();
                    list($realH, $realW, $realX, $realY) = $this->model->getRealCropParameters($size->getHeight(), $size->getWidth());
                    $image
                        ->copy()
                        ->crop(new Point($realX, $realY), new Box($realW, $realH))
                        ->save($filePath);
                }

                if($op === 'thumbnail'){
                    $newSize = ArrayHelper::getValue($operation, 'size', []);
                    if(!$newSize){
                        throw new UnknownPropertyException('size parameter not found');
                    }
                    list($width, $height) = $newSize;
                    Image::getImagine()->open($filePath)->thumbnail(new Box($width, $height))->save($filePath);
                }

                return $fileName;
            }
        }

        return '';
    }

    public function getImagePath($withFileName = false) {
        return \Yii::getAlias($this->savePath);
    }

    private function initModel(){
        $this->model = \Yii::createObject(['class' => $this->modelClass]);
        $this->model->load(\Yii::$app->request->post());

        $this->model->uploadImageFile = UploadedFile::getInstance($this->model, 'uploadImageFile');
    }

    private function save(){
        //        if ($fileName) {
//
//
//
////            list($height, $width) = array_values(Yii::$app->params['groupLogoParams']);
//
//            /** @var Imagick $image */
//            $image = Imagick::open($filePath);
//            $format = $image->getFormat();
//            if ($format !== 'PNG') {
//                $oldFilePath = $filePath;
//                $path = $model->getImagePath();
//                $baseFileName = FileHelper::fileBaseName($fileName);
//                $fileName = Utils::getFileNameUnique($path, $baseFileName, 'png');
//                $filePath = $path . '/' . $fileName;
//                $image->setFormat('PNG')->saveTo($filePath);
//                $image = Imagick::open($filePath);
//                unlink($oldFilePath);
//            }
//            $image->adaptiveResize($width, $height)->saveTo($filePath);
////                $image->smartResize($needWidth, $needHeight)->saveTo($avatarPath);
//
//            $model->file_name = $fileName;
//            $model->update(false, ['file_name']);
//        }

        //        if (Yii::$app->request->isPost) {
//            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
//            $fileName = $model->upload();
//            if($fileName){
//                //Удалим старый файл, если есть
//                if($model->file_name){
//                    $oldFile = $model->getAvatarPath(true);
//                    if(file_exists($oldFile)){
//                        unlink($oldFile);
//                    }
//                    $oldThumb = $model->getAvatarThumbPath();
//                    if(file_exists($oldThumb)){
//                        unlink($oldThumb);
//                    }
//                }
//                $model->file_name = $fileName;
//                $model->update(false, ['file_name']);
//                $avatarPath = $model->getAvatarPath(true);
//                $avatarThumbPath = $model->getAvatarThumbPath();
//                list($height, $width) = array_values(Yii::$app->params['avatarParams']);
//                list($heightThumb, $widthThumb) = array_values(Yii::$app->params['avatarThumbsParams']);
//                //Нужно получить точные размеры, для этого нужно отресайзить и кропнуть.
//                //Но ресайзить и кропать нужно или по ширине или по высоте
//                /** @var Imagick $image */
//                $image = Imagick::open($avatarPath);
//                $image->smartResize($width, $height)->saveTo($avatarPath, false);
//                $image->smartResize($widthThumb, $heightThumb)->saveTo($avatarThumbPath);
//            }
//        }
    }
}