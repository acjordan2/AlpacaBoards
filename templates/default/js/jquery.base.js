$(function() {
		$("img").lazyload();
		$("#qptoggle").click(function () {
			quickpost();
			return false;
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

function quickpost(){
	$("#pageexpander").toggle();
	$("#quickpost").toggle();
	$("#open").toggle();
	$("#close").toggle();
	if ($("#pageexpander").is(":visible")) $("#message").focus()
}

function quickpost_quote(message_id){
	if (!$("#pageexpander").is(":visible")) quickpost();
	data = message_id.split(",");
	id = data[2].split("@");
	req = "/message.php?id="+id[0]+"&topic="+data[1]+"&r="+id[1]+"&output=json";
	$.ajax({url:"/message.php", dataType:"json", data:"id="+id[0]+"&topic="+data[1]+"&r="+id[1]+"&output=json", success:function(result){
		message_body = $("#qpmessage").val();
		message_split = $('#qpmessage').val().split("---");

		message_body = message_split[0];
		sig = message_split[message_split.length-1];
		$("#qpmessage").val(message_body+"<quote msgid=\""+data[0]+","+data[1]+","+data[2]+"\">"+result['message']+"</quote>\n"+"---"+sig);
	}});
	return false;
}

function llmlSpoiler(id){
		$(id).click(function (){
			$(id).toggleClass("spoiler_opened", "spoiler_closed");
			$(id).toggleClass("spoiler_closed", "spoiler_opened");
			$("img").lazyload();
			return false;
	});
}
