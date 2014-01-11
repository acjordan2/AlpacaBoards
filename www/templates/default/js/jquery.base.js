$(function() {
		//Lazy Load Images
		$("img").lazyload();

		//Toggle quickpost box
		$("#qptoggle").click(function () {
			quickpost();
			return false;
		});

		//Toggle image upload box
		$("#btn_upload").click(function () {
			 $("#uploadFrame").toggle(); 
     			 $("#upload").attr("src", "./u.php");
		});

		//AJAX for linkme.tpl
		ajaxPost("#link_vote", "v");
		ajaxPost("#link_fav", "f");

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
	quote_data = message_id.split(",");
	id = quote_data[2].split("@");
	req = "id="+id[0]+"&topic="+quote_data[1]+"&r="+id[1]+"&output=json";
	if(quote_data[0] == "l")
		req += "&link=1";
	$.ajax({url:"./message.php", dataType:"json", data:req, success:function(result){
		message_body = $("#qpmessage").val();
		message_split = $('#qpmessage').val().split("---");

		message_body = message_split[0];
		sig = message_split[message_split.length-1];
		$("#qpmessage").val(message_body+"<quote msgid=\""+quote_data[0]+","+quote_data[1]+","+quote_data[2]+"\">"+result['message']+"</quote>\n"+"---"+sig);
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

function ajaxPost(aForm, submitName){
	var request;
	$(aForm).submit(function(event){
   		var submitValue = $("button[clicked=true]").val()
   		var $form = $(this);
   		var $inputs = $form.find("input, select, button, textarea");
   		var serializedData = $form.serialize();
   		$inputs.prop("disabled", true);
   		request = $.ajax({
   			url: "./ajax.php",
   			type: "post",
   			data: submitName + "="+submitValue+"&"+serializedData
   		});

	    request.done(function (response, textStatus, jqXHR){
	        var obj = JSON.parse(response);
	        $.each(obj, function(k, v){
	        	$("#"+k).text(v);
	        });
	        console.log("Hooray, it worked!");
    	});

	    request.fail(function (jqXHR, textStatus, errorThrown){
	        console.error(
            	"The following error occured: "+
            	textStatus, errorThrown
            );
    	});
    	request.always(function () {
        	$inputs.prop("disabled", false);
    	});
	    event.preventDefault();
	});

	$(aForm + " button").click(function() {
    		$("button", $(this).parents("form")).removeAttr("clicked");
    	$(this).attr("clicked", "true");
	});
}