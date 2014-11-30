/* new activity */

var activity_being_added = false;

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

var saveNewActivity = function(){
	
}

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

/* existing activity */

var displayDetailsBox = function (ev, div_id){
	ev.preventDefault();
	
	var div = $('#'+div_id);
	
	if (div.is(":visible")){
		div.hide();
	} else{
		div.show("fast");
	}
};

var editActivity = function(activityAssocId){
	// first, get information on the activity association being edited
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, action: 'get'},
		success: function(result){
			if (result!=""){
				// successful request 
				
				alert(result);
			}
		}
	});
}

var deleteActivity = function(actAssocId){
	var divToHide = $('#activity_section_'+actAssocId);
	// hide the current activity
	divToHide.hide();
	
	// show a confirm delete section instead
	divToAdd = $('<li class="adding_activity center" id="delete_confirmation_'+actAssocId+'"></li>');
	divToAdd.append('<span class="delete_confirmation">Are you sure you want to remove this activity?<br/>You will be sidelined from your active discussions.</span>');
	divToAdd.append('<input type="button" value="Delete" class="confirmation_button" onclick="confirmDeleteActivity(\''+actAssocId+'\')" /> \
		<input type="button" class="confirmation_button" value="Cancel" onclick="cancelDeleteActivity(\''+actAssocId+'\')" />');
	divToAdd.insertBefore(divToHide);
	divToAdd.show();
}

var confirmDeleteActivity = function(actAssocId){
	
}

var cancelDeleteActivity = function(actAssocId){
	$('#delete_confirmation_'+actAssocId).remove();
	$('#activity_section_'+actAssocId).show();
}

// loads when document finishes loading
$(document).ready(function () {
	// hide all activity drop downs when user clicks anywhere in the window
	$(document).click(function (e) {
		if ($(e.target).closest('[id^=activity_drop_]').length > 0) return;
		$("[id^=activity_edit_]").hide();
    });
});