(function ($) {
    "use strict";
    $(function () {
        /* 'credit_tracker_table' styling */
        $("#credit-tracker-table tr:nth-child(odd)").addClass("odd-row");
        $("#credit-tracker-table td:first-child, table th:first-child").addClass("first");
        $("#credit-tracker-table td:last-child, table th:last-child").addClass("last");
    });
}(jQuery));
