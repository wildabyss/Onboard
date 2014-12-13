var showInterestedFriendsSummary = function(actAssocId){
	var count = $("#interested_friends_summary_"+actAssocId+" li").length;
	if (count > 0){
		$("#interested_friends_summary_"+actAssocId).show("fast");
	}
}

var hideInterestedFriendsSummary = function(actAssocId){
	$("#interested_friends_summary_"+actAssocId).hide();
}


/* loads when document finishes loading */

$(document).ready(function () {
	// autogrow text area
	$('textarea').autogrow();
	
	// hide all activity drop downs when user clicks anywhere in the window
	$(document).click(function (e) {
		if ($(e.target).closest('[id^=activity_drop_]').length > 0) return;
		$("[id^=activity_edit_]").hide();
    });
});