(function ($) {
    "use strict";
    $(function () {
        // Place your administration-specific JavaScript here
    });
}(jQuery));

jQuery(document).ready(function($) {

    $("#ct-validate").click(function () {
        var data = {
            action: 'validate'
        };

        $.post(ajaxurl, data, function (response) {
            //alert('Got this from the server: ' + response);
            var features = jQuery.parseJSON(response);
            $.each(features, function (key, value) {
                var featText = 'OFF';
                var featClass = 'label-off';
                if (value === '1') {
                    featText = 'ON';
                    featClass = 'label-on';
                }
                $("ul[id=credittracker_features] > [id=" + key + "] > span").text(featText);
                $("ul[id=credittracker_features] > [id=" + key + "] > span").removeClass("label-off", "label-on").addClass(featClass);
            });
        });
    });

    $("#ct-mediadata").click(function () {
        var data = {
            action: 'get_media_data',
            source: $("[id$=credit-tracker-source]").val(),
            ident_nr: $("[id$=credit-tracker-ident_nr]").val()
        };

        $.post(ajaxurl, data, function (response) {
            //alert('Got this from the server: ' + response);
            var mediadata = jQuery.parseJSON(response);
            $("[id$=credit-tracker-author]").val(mediadata.author);
            $("[id$=credit-tracker-publisher]").val(mediadata.publisher);
            $("[id$=credit-tracker-license]").val(mediadata.license);
            $("[id$=credit-tracker-link]").val(mediadata.link);
        });
    });

});
