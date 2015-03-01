var showInterestedFriendsSummary = function(actAssocId){
	var count = $("#interested_friends_summary_"+actAssocId+" li").length;
	if (count > 0){
		$("#interested_friends_summary_"+actAssocId).show("fast");
	}
}

var hideInterestedFriendsSummary = function(actAssocId){
	$("#interested_friends_summary_"+actAssocId).hide();
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

$(document).ready(function () {
	// autogrow text area
	$('textarea').autogrow();
});