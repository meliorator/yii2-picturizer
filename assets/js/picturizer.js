
(function () {
    "use strict";
    $.fn.picturizer = function(options) {

        var settings = $.extend( {
            'location'         : 'top',
            'background-color' : 'blue'
        }, options);

        function readURL(input) {
            $('.preview').show();
            $('#blah').hide();
            $('.preview').after('<img id="blah" src="#" alt="your image" style="display:none;"/>');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    }

})(jQuery);