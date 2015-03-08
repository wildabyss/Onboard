
var activitySectionClick = function(userId, activityAssocId){
	changeBrowserURL('/id/'+userId+'/actid/'+activityAssocId);
}

var likeActivity = function (event, activityAssocId, friendId){
	// IE8 fix
	event = event || window.event;
	//var ele = event.target || event.srcElement;
	button = $('[id*=onboard_button_'+activityAssocId+']');
	
	if (event.stopPropagation)
		event.stopPropagation();
	else
		event.cancelBubble = true;
	
	// default type
	if (typeof button.attr("type") == typeof undefined || button.attr("type") == false){
		button.attr("type", "onboard");
	}
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, action: button.attr('type')},
		success: function(result){
			if (result!=""){
				// successful request 
				
				var respArr = JSON.parse(result);
				
				// change interest tally
				var intTallyEl = $("[id*=interest_tally_"+activityAssocId+"]");
				if (intTallyEl.length>0) {
					intTallyEl.html(respArr.interest_count + " interests");
				}
				
				// if there's a popup, then change its appearance
				if ($('#interest_details_'+activityAssocId).length){
					expandActivity(activityAssocId, friendId);
				}
				
				// change button
				if (button.attr('type') == "onboard"){
					button.attr('type', "leave");
					button.html("Leave");
				} else {
					button.attr('type', "onboard");
					button.html("Onboard!");
				}
			}
		}
	});
};


/* change browser URL */

var changeBrowserURL = function(url){
	if(history.pushState) {
	    history.pushState(null, null, url);
	}
	else {
	    location.hash = url;
	}
}


/* loads when document finishes loading */

var removePopup = function(restoreURL){
	var height = parseInt($('#super_global_wrapper').css('margin-top'));
	
	// remove popup
	$('.popup_container').hide();
	
	// remove haze
	$('#haze').hide();
	
	// restore main content appearance
	$('#super_global_wrapper').removeAttr('style');
	$(window).scrollTop(-height);
	
	// change URL
	if (restoreURL){
		var url = document.URL;
		var link = url.split("/actid")[0];
		changeBrowserURL(link);
	}
}

$(document).ready(function () {
	// autogrow text area
	$('textarea').autogrow();
	
	// remove haze and popup
	$('#haze').click(function (e){
		// popup box
		if ($(e.target).closest('.popup_container').length <= 0) {
			removePopup(true);
		}
	});
});