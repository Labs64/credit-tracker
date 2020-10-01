(function ($) {
    "use strict";
    $(function () {
        /* 'credit_tracker_table' styling */
        $("#credit-tracker-table tr:nth-child(odd)").addClass("odd-row");
        $("#credit-tracker-table td:first-child, table th:first-child").addClass("first");
		$("#credit-tracker-table td:last-child, table th:last-child").addClass("last");
		
		const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
		const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
			v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
			)(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));
		document.querySelectorAll('#credit-tracker-table th').forEach(th => th.addEventListener('click', (() => {
			const table = th.closest('table');
			const tbody = table.querySelector('tbody');
			Array.from(table.querySelectorAll('tbody tr'))
				.sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
				.forEach(tr => tbody.appendChild(tr) );
		})));
    });
}(jQuery));
