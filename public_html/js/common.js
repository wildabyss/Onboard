var showInterestedFriendsSummary = function(showId){
	var count = $("#"+showId+" li").length;
	if (count > 0){
		$("#"+showId).show("fast");
	}
}

var hideInterestedFriendsSummary = function(showId){
	$("#"+showId).hide();
}

var activitySectionClick = function(userId, activityAssocId){
	changeBrowserURL('/id/'+userId+'/actid/'+activityAssocId);
}


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

var removePopup = function(){
	var height = parseInt($('#super_global_wrapper').css('margin-top'));
	
	// remove popup
	$('.popup_container').hide();
	
	// remove haze
	$('#haze').hide();
	
	// restore main content appearance
	$('#super_global_wrapper').removeAttr('style');
	$(window).scrollTop(-height);
}

$(document).ready(function () {
	// autogrow text area
	$('textarea').autogrow();
	
	// remove haze and popup
	$('#haze').click(function (e){
		// popup box
		if ($(e.target).closest('.popup_container').length <= 0) {
			removePopup();
		}
	});
});