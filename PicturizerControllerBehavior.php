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
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class PicturizerControllerBehavior extends Behavior {

    public $savePath;

    public function init() {
        if (!$this->savePath) {
            throw new CommonException('Property savePath is not defined.');
        }
        parent::init();
    }

    public function saveUploadedImage() {
        if (\Yii::$app->request->isPost) {
            $model = new PicturizerModel();
            $model->load(\Yii::$app->request->post());

            $model->uploadImageFile = UploadedFile::getInstance($model, 'uploadImageFile');
            list($success, $fileName) = $model->uploadImageTo($this->getImagePath());
            if ($success) {
                $filePath = $this->getImagePath() . '/' . $fileName;

                $imagine = new \Imagine\Imagick\Imagine();
                $image = $imagine->open($filePath);
                $size = $image->getSize();
                list($realH, $realW, $realX, $realY) = $model->getRealCropParameters($size->getHeight(), $size->getWidth());
                $image
                    ->copy()
                    ->crop(new Point($realX, $realY), new Box($realW, $realH))
                    ->save($filePath);
//                $ff = Image::crop(
//                    $filePath,
//                    (int)$model->widthImageFile,
//                    (int)$model->heightImageFile,
//                    [(int)$model->leftImageFile, (int)$model->topImageFile]
//                );//->save($filePath);

                return $fileName;
            }
        }

        return '';
    }

    public function getImagePath($withFileName = false) {
        return \Yii::getAlias($this->savePath);
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