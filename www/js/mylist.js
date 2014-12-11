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
		$('#new_activity_alias').focus();
	}
};

var saveNewActivity = function(listId){
	var inputActAlias = $.trim($('#new_activity_alias').val());
	var inputActDescr = $.trim($('#new_activity_description').val());
	var inputActCats = $.trim($('#new_activity_categories').val());
	
	// validate the fields prior to submitting
	if (inputActAlias == "")
		return;

	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_alias: inputActAlias, activity_descr: inputActDescr, activity_cats: inputActCats, 
			activity_list: listId, action: 'save_new'},
		beforeSend: function(){
			// disable buttons
			$('#save_activity_button_new').attr("disabled", true);
			$('#cancel_activity_button_new').attr("disabled", true);
		},
		success: function(result){
			$('#adding_activity').hide();
			$("#modification_bar").show();
			
			if (result != ""){
				// server has returned result
				var newSection = $(result);
				newSection.insertAfter('#adding_activity');
				newSection.show();
				
				// erase previous entries
				$('#new_activity_alias').val('');
				$('#new_activity_description').val('');
				$('#new_activity_categories').val('');
			} else{
				var noActMsg = $('#no_activity_msg');
				if (noActMsg.is(":hidden")){
					noActMsg.show();
				}
			}
			
			activity_being_added = false;
			// enable buttons
			$('#save_activity_button_new').attr("disabled", false);
			$('#cancel_activity_button_new').attr("disabled", false);
		}
	});
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
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, action: 'get'},
		success: function(result){
			if (result!=""){
				// successful request 
				
				var li_section = $('#activity_section_'+activityAssocId);
				li_section.hide();
				$(result).insertBefore(li_section);
				$('#edit_activity_'+activityAssocId).show();
			}
		}
	});
}

var saveActivity = function(activityAssocId){
	var inputActAlias = $.trim($('#edit_activity_alias_'+activityAssocId).val());
	var inputActDescr = $.trim($('#edit_activity_description_'+activityAssocId).val());
	var inputActCats = $.trim($('#edit_activity_categories_'+activityAssocId).val());
	
	// validate the fields prior to submitting
	if (inputActAlias == "")
		return;
	
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, activity_alias: inputActAlias, 
			activity_descr: inputActDescr, activity_cats: inputActCats, action: 'save'},
		beforeSend: function(){
			// disable buttons
			$('#save_activity_button_'+activityAssocId).attr("disabled", true);
			$('#cancel_activity_button_'+activityAssocId).attr("disabled", true);
		},
		success: function(result){
			$('#edit_activity_'+activityAssocId).remove();
			
			// redisplay view
			if (result != ""){
				$('#activity_section_'+activityAssocId).replaceWith(result);
			}
			$('#activity_section_'+activityAssocId).show();
		}
	});
}

var cancelSaveActivity = function(activityAssocId){
	$('#edit_activity_'+activityAssocId).remove();
	$('#activity_section_'+activityAssocId).show();
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
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'delete'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#delete_confirmation_'+actAssocId).remove();
				$('#activity_section_'+actAssocId).remove();
			}
		}
	});
}

var cancelDeleteActivity = function(actAssocId){
	$('#delete_confirmation_'+actAssocId).remove();
	$('#activity_section_'+actAssocId).show();
}

var markAsCompleted = function(event){
	var actAssocId = event.data.actAssocId;
	
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'mark_complete'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#activity_title_'+actAssocId).addClass('completed_activity');
				$('#mark_complete_'+actAssocId).html('Mark as active');
				
				// bind a new click handler
				$('#mark_complete_'+actAssocId).off('click').on("click", {actAssocId:actAssocId}, markAsActive);
			}
		}
	});
}

var markAsActive = function(event){
	var actAssocId = event.data.actAssocId;
	
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'mark_active'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#activity_title_'+actAssocId).removeClass('completed_activity');
				$('#mark_complete_'+actAssocId).html('Mark as completed');

				// bind a new click handler
				$('#mark_complete_'+actAssocId).off('click').on("click", {actAssocId:actAssocId}, markAsCompleted);
			}
		}
	});
}

var expandActivity = function(actAssocId) {
	var expandButton = $('#expand_'+actAssocId);
	if (expandButton.attr('action') == "expand"){
		if ($('#interest_details_'+actAssocId).length){
			$('#interest_details_'+actAssocId).show();
			expandButton.attr('action', 'hide');
		} else {
			$.ajax({
				url:	"ajaxActivityAssociation",
				type: 	"post",
				data:	{activity_assoc: actAssocId, action: 'expand_activity_details'},
				success: function(result){
					if (result != ""){
						// successful request 

						$('#interest_info_'+actAssocId).append(result);
						expandButton.attr('action', 'hide');
					}
				}
			});
		}
	} else if (expandButton.attr('action') == "hide"){
		$('#interest_details_'+actAssocId).hide();
		expandButton.attr('action', 'expand');
	}
}


/* loads when document finishes loading */

$(document).ready(function () {
	// hide all activity drop downs when user clicks anywhere in the window
	$(document).click(function (e) {
		if ($(e.target).closest('[id^=activity_drop_]').length > 0) return;
		$("[id^=activity_edit_]").hide();
    });
});