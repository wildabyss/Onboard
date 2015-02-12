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
	//document.location.hash = 'activity_section_'+activityAssocId;
	if(history.pushState) {
	    history.pushState(null, null, '/id/'+userId+'#activity_section_'+activityAssocId);
	}
	else {
	    location.hash = '/id/'+userId+'#activity_section_'+activityAssocId;
	}
}


/* loads when document finishes loading */

$(document).ready(function () {
	// autogrow text area
	$('textarea').autogrow();
});