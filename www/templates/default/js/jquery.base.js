jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      var sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  });
}
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

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

        $("form").submit(function(e){
            var payload = "{\"messages\":" + JSON.stringify($('form').serializeObject()) + "}";
            
            $.ajax({
                type: "POST",
                url: "./api.php",
                data: payload,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function() { quickpost(); }
            });

            return false;
        });

    MultiAjaxAutoComplete('#tags', './ajax.php?action=link_tags');



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
		$("#qpmessage").insertAtCaret("<quote msgid=\""+quote_data[0]+","+quote_data[1]+","+quote_data[2]+"\">"+result['message']+"</quote>");
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
function MultiAjaxAutoComplete(element, url) {
    $(element).select2({
        placeholder: "Search for a tag",
        minimumInputLength: 1,
        multiple: true,
        ajax: {
            url: url,
            dataType: 'json',
            data: function(term, page) {
                return {
                    q: term,
                    page_limit: 10,
                };
            },
            results: function(data, page) {
                return {
                    results: data.tags
                };
            }
        },
        formatResult: formatResult,
        formatSelection: formatSelection,
        initSelection: function(element, callback) {
            var data = [];
            $(element.val().split(",")).each(function(i) {
                var item = this.split(':');
                data.push({
                    id: item[0],
                    title: item[1]
                });
            });
            //$(element).val('');
            callback(data);
        }
    });
};

function formatResult(tags) {
    return '<div>' + tags.title + '</div>';
};

function formatSelection(data) {
    checkParentTag(data.id);
    return data.title;
};

function checkParentTag(data) {
    var value = $('#tags').val();
    if(value) { 
        value += ","+data;
    } else {
        value = data;
    }
    $.ajax({
        type: "POST",
        url: "./ajax.php?action=link_checkParentTag",
        data: "tags="+value,
        success: function (response) {
            if(response.length != 0) {
                alert("Parent/Child Relationshiop Fail. Some of your tags are redundant");
            }
        },
        dataType: "json"
    });
}



function playSound( url ){   
  document.getElementById("sound").innerHTML="<embed src='"+url+"' hidden=true autostart=true loop=false>";
}