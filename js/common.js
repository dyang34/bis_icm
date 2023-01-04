var setCookie = function(name, value, exp) {
	var date = new Date();
	date.setTime(date.getTime() + exp*24*60*60*1000);
	document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
};

var getCookie = function(name) {
	var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return value? value[2] : null;
};

var goPageTop = function(){
	$('#content').animate({scrollTop:0},200);
}

//*********** Modal layerPopUp Script Start ************
$(document).on('click', '.btn_modal', function() {
	var btn = $(this).attr("id");
	$("#modal").removeAttr("class").addClass(btn);
});

$(document).on('click', '.close_modal', function() {
	$("#modal").addClass("out");
});
//*********** Modal layerPopUp Script End ************