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
            alert('Got this from the server: ' + response);
        });
    });
});
