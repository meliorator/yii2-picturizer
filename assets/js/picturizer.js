(function () {
    "use strict";
    $.fn.picturizer = function (options, minW, minH) {

        var $wrapper = $(this),

            settings = $.extend({
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
            }, options),

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

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function () {
                            if (this.width < minW || this.height < minH) {
                                alert('Image need biggest');
                                return false;
                            }
                            // var x1 = (this.width - width) / 2;
                            // var y1 = (this.height - height) / 2;
                            // var x2 = x1 + width;
                            // var y2 = y1 + height;

                            picturizer.initPreview();
                        };

                    };

                    picturizer.$button.on('change', function () {
                        if (this.files && this.files[0]) {
                            picturizer.reader.readAsDataURL(this.files[0]);
                        }
                    });

                    // picturizer.initPreview();
                },

                initPreview: function () {
                    picturizer.$preview.Jcrop(settings);

                    picturizer.$viewHeight.val(picturizer.$preview.height());
                    picturizer.$viewWidth.val(picturizer.$preview.width());
                },

                clearPreview: function () {
                    if (picturizer.$preview) {
                        var jCrop = picturizer.$preview.data('Jcrop');
                        if(jCrop != undefined){
                            jCrop.destroy();
                        }

                        picturizer.$preview.remove();
                        picturizer.$preview = null;
                    }
                }
            };

        picturizer.init();
    }

})(jQuery);