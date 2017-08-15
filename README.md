Picturizer
==========
Yii-Framework extension for uploading and cropping images. 

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist meliorator/yii2-picturizer "*"
```

or add

```
"meliorator/yii2-picturizer": "*"
```

to the require section of your `composer.json` file.

Differences another extensions for JCrop library
---------------------------------
Picturizer does not use the ajax-simple-upload-button or another ajax upload methods. Picturizer use only the HTTP POST method for upload files.

Usage
-----

Once the extension is installed, simply use it in your code by :

```php
<?= \meliorator\picturizer\Picturizer::widget(); ?>
```

and need attach behavior PicturizerControllerBehavior to controller. Example :


```php
use meliorator\picturizer\PicturizerControllerBehavior;
```
```php
 return [                
                'avatar' => [
                    'class' => PicturizerControllerBehavior::className(),
                    'savePath' => '@webroot/images/employees' 
                ]
            ];
         
```
and call saveUploadedImage method for save image :
```php
$newFileName = $this->saveUploadedImage();
```
        
 