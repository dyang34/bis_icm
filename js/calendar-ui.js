// 달력 script
$(document).ready(function() {
	$.datepicker.setDefaults({
		showOn: "both",
		dateFormat: "yy-mm-dd",
		buttonImage: "/images/admin/icon-calendar.svg",
		buttonImageOnly: true,
		showOtherMonths: true,
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		buttonText: "Select date",
	});
});