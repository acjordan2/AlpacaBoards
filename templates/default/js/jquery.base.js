$(function() {
		$("img").lazyload();
		$("#qptoggle").click(function () {
			$("#pageexpander").toggle();
			$("#quickpost").toggle();
			$("#open").toggle();
			$("#close").toggle();
			if ($("#pageexpander").is(":visible")) $("#message").focus()
		});

});

$(window).keypress(function (a) {
    if (a.charCode == 96) {
        if (!$("#qpmessage").is(":focus")) {
            $("#qptoggle").click();
            a.preventDefault();
            $("#qpmessage").focus();
        }
    }
    return a;
});

function llmlSpoiler(id){
		$(id).click(function (){
			$(id).toggleClass("spoiler_opened", "spoiler_closed");
			$(id).toggleClass("spoiler_closed", "spoiler_opened");
			$("img").lazyload();
			return false;
	});
}
