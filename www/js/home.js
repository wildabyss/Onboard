var activity_being_added = false;

var displayDetailsBox = function (ev, div_id){
	ev.preventDefault();
	
	var div = $('#'+div_id);
	
	if (div.is(":visible")){
		div.hide();
	} else{
		div.show("fast");
	}
};

var addNewActivity = function(){
	if (!activity_being_added){
		activity_being_added = true;
		$("#modification_bar").hide();
		
		var noActMsg = $('#no_activity_msg');
		if (noActMsg.is(":visible")){
			noActMsg.hide();
		}
		
		$('#adding_activity').show("fast");
	}
};

var cancelNewActivity = function(){
	if (activity_being_added){
		$('#adding_activity').hide();
		$("#modification_bar").show();
		
		var noActMsg = $('#no_activity_msg');
		if (noActMsg.is(":hidden")){
			noActMsg.show();
		}
		
		activity_being_added = false;
	}
};

// loads when document finishes loading
$(document).ready(function () {
	// hide all activity drop downs when user clicks anywhere in the window
	$(document).click(function (e) {
		if ($(e.target).closest('[id^=activity_drop_]').length > 0) return;
		$("[id^=activity_edit_]").hide();
    });
});