<?php
/**
 * Created by Laba Mykola.
 * Date: 14.08.17
 * Time: 22:19
 */

namespace meliorator\picturizer;


class FileHelper extends \yii\helpers\FileHelper {

    public static function getFileNameUnique($directory, $fileName, $extension) {
        $path = $directory;
        if ($path) {
            if (substr($path, strlen($path) - 1, 1) !== '/') {
                $path .= '/';
            }
        } else {
            return null;
        }

        $counter = 0;
        $baseName = $fileName;

        if (strpos($extension, '.') === 0) {
            $ext = $extension;
        } else {
            $ext = '.' . $extension;
        }
        $resultName = $baseName . $ext;
        while (file_exists($path . $resultName)) {
            $counter++;
            $resultName = $baseName . '-' . $counter . $ext;
        }
        return $resultName;
    }
}