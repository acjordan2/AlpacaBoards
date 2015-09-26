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

        $(".vote_button").click(function() {
            alert($(this).text());
            alert("asdfasdf");
            $("#value").val("10");
        });

        $('span.spoiler_closed').each(function(){}).on('click', function(e) {
            $(this).toggleClass("spoiler_closed", "spoiler_opened");
            $(this).toggleClass("spoiler_opened", "spoiler_closed");
            $("img").lazyload();
            return false;
        });

        $("#link_vote").submit(function(e) {
            var payload = "{\"links\":" + JSON.stringify($('form#link_vote').serializeObject()) + "}";
 
            $.ajax({
                type: "POST",
                url: "./api.php",
                data: payload,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(data) {
                        $.each(data, function(k, v){
                        $("#"+k).text(v);
                    });
                }
            
            });

            return false;
        });

        $("#link_fav").submit(function(e) {
            var payload = "{\"links\":" + JSON.stringify($('form#link_fav').serializeObject()) + "}";
 
            $.ajax({
                type: "POST",
                url: "./api.php",
                data: payload,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(data) {
                        $.each(data, function(k, v){
                        $("#"+k).text(v);
                        if (k == "state" && v == true) {
                            $("#f").text("Remove from Favorites");
                        } else {
                            $("#f").text("Add to Favorites");
                        }
                    });
                }
            
            });

            return false;
        });


        $("#quickpost").submit(function(e){
            var payload = "{\"messages\":" + JSON.stringify($('form').serializeObject()) + "}";
            
            $.ajax({
                type: "POST",
                url: "./api.php",
                data: payload,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(data) { 
                    if (data.status == "failed") {
                        $("#status_message").text(data.message);
                    } else {
                        quickpost();
                    }
                }
            });

            return false;
        });

    MultiAjaxAutoComplete('#tags', './api.php');



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
        ajax:  {
            url: './api.php',
            type: "POST",
            dataType: 'json',
            data: function(params){
              return  "{\"tags\": {\"action\":\"getTags\", \"type\":2, \"title\":\"" + params + "\"}}"
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
    var value = "";
    if ($('#tags').val().length != 0 && $('#tags').val().split(",") < 2) {
        alert($('#tags').val());
        value = "{\"id\":" + $('#tags').val().split(":")[0] + "}";
    } else {
        var tags = $('#tags').val().split(",");
        for (var i=0; i < tags.length; i++) {
            if (tags[i] != "") {
                value += ",{\"id\":" + tags[i].split(":")[0] + "}";
            }
        }
    }
    if(value) { 
        value += ",{\"id\":" + data + "}";
    } 
    if (value.charAt(0) == ",") {
        value = value.substr(1);
    }
    $.ajax({
        type: "POST",
        url: "./api.php",
        dataType: "json",
        data: "{\"tags\": {\"action\":\"checkConflicts\", \"data\":[" + value + "]}}",
        success: function (response) {
            if(response.length != 0 && response.error == null) {
                alert("Error: Tag ID " + tags[tags.length - 1] + " and " + tags[tags.length - 2] + " cannot be tagged together");
            }
        },
        dataType: "json"
    });
}



function playSound( url ){   
  document.getElementById("sound").innerHTML="<embed src='"+url+"' hidden=true autostart=true loop=false>";
}
