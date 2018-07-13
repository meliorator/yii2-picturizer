(function () {
    "use strict";
    $.fn.picturizer = function (jCropOptions, commonOptions) {

        var $wrapper = $(this),

            minW = commonOptions['minWidth'],
            minH = commonOptions['minHeight'],

            jCropsettings = $.extend({
                aspectRatio: 1,
                bgColor: 'white',
                bgOpacity: 0.3,
                minSize: [minW, minH],
                setSelect: [0, 0, minW, minH],
                onSelect: function (c) {
                    $wrapper.find('input.left').val(c.x);
                    $wrapper.find('input.top').val(c.y);
                    $wrapper.find('input.width').val(c.w);
                    $wrapper.find('input.height').val(c.h);
                }
            }, jCropOptions),

            commonSettings = $.extend({
                previewMaxHeight: 0,
                previewMaxWidth:0
            },commonOptions),

            picturizer = {
                reader: null,
                $preview: $wrapper.find('.preview'),
                $previewWrapper: $wrapper.find('.crop-preview'),
                $button: $wrapper.find('.crop-action input'),
                $viewHeight: $wrapper.find('input.viewHeight'),
                $viewWidth: $wrapper.find('input.viewWidth'),
                init: function () {
                    picturizer.reader = new FileReader();
                    picturizer.reader.onload = function (e) {
                        picturizer.clearPreview();
                        picturizer.$previewWrapper.append('<img src="' + e.target.result + '" class="preview preview img-responsive">');
                        picturizer.$preview = picturizer.$previewWrapper.find('.preview');

                        if(commonSettings.previewMaxHeight > 0){
                            picturizer.$preview.css('max-height', commonSettings.previewMaxHeight);
                        }

                        if(commonSettings.previewMaxWidth > 0){
                            picturizer.$preview.css('max-width', commonSettings.previewMaxWidth);
                        }

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function () {
                            if (this.width < minW || this.height < minH) {
                                alert('Image need biggest');
                                return false;
                            }

                            picturizer.initPreview();
                        };

                    };

                    picturizer.$button.on('change', function () {
                        if (this.files && this.files[0]) {
                            picturizer.reader.readAsDataURL(this.files[0]);
                        }
                    });
                },

                initPreview: function () {
                    if(jCropsettings['withoutCrop'] == undefined){
                        picturizer.$preview.Jcrop(jCropsettings);
                    }

                    picturizer.$viewHeight.val(picturizer.$preview.height());
                    picturizer.$viewWidth.val(picturizer.$preview.width());
                },

                clearPreview: function () {
                    if (picturizer.$preview) {

                        if(jCropsettings['withoutCrop'] == undefined){
                            var jCrop = picturizer.$preview.data('Jcrop');
                            if(jCrop != undefined){
                                jCrop.destroy();
                            }
                        }

                        picturizer.$preview.remove();
                        picturizer.$preview = null;
                    }
                }
            };

        picturizer.init();
    }

})(jQuery);