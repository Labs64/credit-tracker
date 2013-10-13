(function ($) {
    "use strict";
    $(function () {
        // Place your administration-specific JavaScript here
    });
}(jQuery));

jQuery(document).ready(function ($) {
    $("#validate").click(function () {
        var data = {
            action: 'validate'
        };

        $.post(ajaxurl, data, function (response) {
            // alert('Got this from the server: ' + response);
            var features = jQuery.parseJSON(response);
            $.each(features, function (key, value) {
                var featText = 'OFF';
                var featClass = 'label-off';
                if (value === '1') {
                    featText = 'ON';
                    featClass = 'label-on';
                }
                $("ul[id=ct_features] > [id=" + key + "] > span").text(featText);
                $("ul[id=ct_features] > [id=" + key + "] > span").removeClass("label-off", "label-on").addClass(featClass);
            });
        });
    });
});
