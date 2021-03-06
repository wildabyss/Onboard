/* new activity */

var activity_being_added = false;
var holding_key = "";

var showInterestedFriendsSummary = function(showId){
	var count = $("#"+showId+" li").length;
	if (count > 0){
		$("#"+showId).show("fast");
	}
}

var hideInterestedFriendsSummary = function(showId){
	$("#"+showId).hide();
}

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
		url:	"/ajaxActivityAssociation",
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
				$('#no_activity_msg').show();
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
	// IE8 fix
	ev = ev || window.event;
	if(event.preventDefault) 
		event.preventDefault();
	else
		event.returnValue = false;
	
	var div = $('#'+div_id);
	
	if (div.is(":visible")){
		div.hide();
	} else{
		div.show("fast");
	}
};

var editActivity = function(activityAssocId){
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, action: 'edit'},
		success: function(result){
			if (result!=""){
				// successful request 
				
				var li_section = $('#popup_basic_activity_'+activityAssocId);
				li_section.hide();
				$(result).insertBefore(li_section);
				$('#edit_activity_'+activityAssocId).show();
			}
		}
	});
}

var saveActivity = function(activityAssocId, userId){
	var inputActAlias = $.trim($('#edit_activity_alias_'+activityAssocId).val());
	var inputActDescr = $.trim($('#edit_activity_description_'+activityAssocId).val());
	var inputActCats = $.trim($('#edit_activity_categories_'+activityAssocId).val());
	
	// validate the fields prior to submitting
	if (inputActAlias == "")
		return;
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, activity_alias: inputActAlias, 
			activity_descr: inputActDescr, activity_cats: inputActCats, action: 'save'},
		beforeSend: function(){
			// disable buttons
			$('#save_activity_button_'+activityAssocId).attr("disabled", true);
			$('#cancel_activity_button_'+activityAssocId).attr("disabled", true);
		},
		success: function(result){
			// replace the element in the activity list
			if (result != ""){
				$('#basic_activity_'+activityAssocId).parent().replaceWith(result);
			}
			// redraw the popup
			$('#activity_popup_container_'+activityAssocId).remove();
			expandActivity(activityAssocId, userId);
		}
	});
}

var cancelSaveActivity = function(activityAssocId){
	$('#edit_activity_'+activityAssocId).remove();
	$('#popup_basic_activity_'+activityAssocId).show();
}

var deleteActivity = function(actAssocId){
	var divToHide = $('#popup_basic_activity_'+actAssocId);
	// hide the current activity
	divToHide.hide();
	
	// show a confirm delete section instead
	divToAdd = $('<div class="center activity_basic_container activity_edit_container" id="delete_confirmation_'+actAssocId+'"></div>');
	divToAdd.append('<span class="delete_confirmation">Are you sure you want to remove this activity?<br/>You will be sidelined from your active discussions.</span>');
	divToAdd.append('<input type="button" value="Delete" class="confirmation_button" onclick="confirmDeleteActivity(\''+actAssocId+'\')" /> \
		<input type="button" class="confirmation_button" value="Cancel" onclick="cancelDeleteActivity(\''+actAssocId+'\')" />');
	divToAdd.insertBefore(divToHide);
	divToAdd.show();
}

var confirmDeleteActivity = function(actAssocId){
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'delete'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#delete_confirmation_'+actAssocId).remove();
				
				// remove from activity list
				$('#activity_section_'+actAssocId).remove();
				// remove the popup
				removePopup(true);
			}
		}
	});
}

var cancelDeleteActivity = function(actAssocId){
	$('#delete_confirmation_'+actAssocId).remove();
	$('#popup_basic_activity_'+actAssocId).show();
}

var markAsCompleted = function(event){
	// IE8 fix
	event = event || window.event;
	
	var actAssocId = event.data.actAssocId;
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'mark_complete'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#popup_activity_title_'+actAssocId).addClass('completed_activity');
				$('#activity_title_'+actAssocId).addClass('completed_activity');
				$('#mark_complete_'+actAssocId).html('Mark as active');
				
				// bind a new click handler
				$('#mark_complete_'+actAssocId).off('click').on("click", {actAssocId:actAssocId}, markAsActive);
			}
		}
	});
}

var markAsActive = function(event){
	// IE8 fix
	event = event || window.event;
	
	var actAssocId = event.data.actAssocId;
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'mark_active'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				$('#popup_activity_title_'+actAssocId).removeClass('completed_activity');
				$('#activity_title_'+actAssocId).removeClass('completed_activity');
				$('#mark_complete_'+actAssocId).html('Mark as completed');

				// bind a new click handler
				$('#mark_complete_'+actAssocId).off('click').on("click", {actAssocId:actAssocId}, markAsCompleted);
			}
		}
	});
}

/* completeRedraw is optional */
var expandActivity = function(actAssocId, userId, completeRedraw) {
	// remove any existing popup
	removePopup(false);
	
	// change URL to reflect activity assoc id
	if (completeRedraw){
		changeBrowserURL('/id/'+userId+'/actid/'+actAssocId);
	
		// change the main content appearance, maintain the scroll position and remove the scroll bar
		var height = $(window).scrollTop();
		$('#super_global_wrapper')
			.css('position','fixed')
			.css('margin-top', -height);
	}
	
	// show haze over the original content
	var haze = $('#haze');
	haze.show();
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: actAssocId, user_id: userId, action: 'expand_activity_details'},
		success: function(result){
			if (result != ""){
				// successful request 
				
				// show the popup
				$('<div class="fixed_center popup_container" style="display:none" id="activity_popup_container_'+actAssocId+'"></div>').insertAfter(haze);
				$('#activity_popup_container_'+actAssocId)
					.html(result)
					.fadeIn();
				
				// scroll message to bottom
				var container = $('#discussion_main_'+actAssocId+' div.message_container').get(0);
				if (container){
					container.scrollTop = container.scrollHeight;
				}
			} else {
				removePopup(true);
			}
		}
	});

}

var facebook_discussion_add = function(event, actAssocId){
	// IE8 fix
	event = event || window.event;
	
	$.ajax({
		url:	"/ajaxDiscussion",
		type: 	"post",
		data:	{activity_assoc: actAssocId, action: 'facebook_group_new'},
		success: function(result){
			if (result == 1){
				// successful request 
				
				
			}
		}
	});
}

var discussion_add = function(event, actAssocId){
	// IE8 fix
	event = event || window.event;
	
	// only allow one new discussion to be added at a time
	if ($("[id^=discussion_tab_new_]").length == 0){
		// send ajax request
		$.ajax({
			url:	"/ajaxDiscussion",
			type: 	"post",
			data:	{action: 'discussion_add', activity_assoc: actAssocId},
			success: function(result){
				if (result != ""){
					// successful request 
					
					$(result).insertAfter('#discussion_tab_add_'+actAssocId);
					$('#discussion_title_new_'+actAssocId).focus();
				}
			}
		});
	}
}

var discussion_add_tab_keydown = function(event, actAssocId){
	// IE8 fix
	event = event || window.event;
	var target = event.target || event.srcElement;
	
	if (event.keyCode==13){
		// enter

		// prevent newline
		if(event.preventDefault) 
			event.preventDefault();
		else
			event.returnValue = false;
		
		// validate name
		if ($.trim(target.innerHTML) == "")
			return;
		
		// perform save
		$.ajax({
			url:	"/ajaxDiscussion",
			type: 	"post",
			data:	{action: 'discussion_new', activity_assoc: actAssocId, name: target.innerHTML},
			success: function(discussionId){
				if ($.isNumeric(discussionId)){
					// successful request 
					
					// perform ajax to retrieve the discussion
					$.ajax({
						url:	"/ajaxDiscussion",
						type: 	"post",
						data:	{action: 'discussion_switch', discussion_id: discussionId, activity_assoc: actAssocId},
						success: function(result){
							if (result != ""){
								// successful request 
								
								// change this tab's appearance
								var tab = $('#discussion_tab_new_'+actAssocId);
								var tabTitle = $('#discussion_title_new_'+actAssocId);
								tabTitle.attr('contenteditable', 'false');
								tab.removeClass('discussion_tab_new');
								tab.addClass('discussion_tab_active');
								// change its id
								tab.attr('id', 'discussion_tab_'+discussionId);
								tabTitle.removeAttr('id');
								
								// tie the onclick event
								tab.attr('onclick', "discussion_switch('"+discussionId+"', '"+actAssocId+"')");
								
								// remove appearances on other tabs
								var siblings = tab.siblings();
								for (i=0; i<siblings.length; i++){
									$(siblings[i]).removeClass("discussion_tab_active");
								}
								
								// set the discussion msgs
								$('#discussion_main_'+actAssocId).html(result);
								var msg_container = $('#discussion_main_'+actAssocId+' div.message_container');
								msg_container[0].scrollTop = msg_container[0].scrollHeight;
							}
						}
					});
				}
			}
		});
		
	} else if (event.keyCode==27){
		// esc
		
		$('#discussion_tab_new_'+actAssocId).remove();
	}
}

var discussion_switch = function(discussionId, actAssocId){
	// add tab appearance
	$("#discussion_tab_"+discussionId).addClass("discussion_tab_active");
	
	// remove appearances on other tabs
	var siblings = $("#discussion_tab_"+discussionId).siblings();
	for (i=0; i<siblings.length; i++){
		$(siblings[i]).removeClass("discussion_tab_active");
	}
	
	// send ajax request
	$.ajax({
		url:	"/ajaxDiscussion",
		type: 	"post",
		data:	{action: 'discussion_switch', discussion_id: discussionId, activity_assoc: actAssocId},
		success: function(result){
			if (result != ""){
				// successful request 
				
				$('#discussion_main_'+actAssocId).html(result);
				var msg_container = $('#discussion_main_'+actAssocId+' div.message_container');
				msg_container[0].scrollTop = msg_container[0].scrollHeight;
			}
		}
	});
}

var discussion_leave = function(discussionId, userId, actAssocId){
	// send ajax request
	$.ajax({
		url:	"/ajaxDiscussion",
		type: 	"post",
		data:	{action: 'discussion_leave', discussion_id: discussionId, activity_assoc: actAssocId},
		success: function(result){
			if (result == 1){
				// successful request 
				
				// refresh the activity detail section
				expandActivity(actAssocId, userId);
			}
		}
	});
}

var discussion_msg_keydown = function(event, discussionId, actAssocId){
	// IE8 fix
	event = event || window.event;
	var target = event.target || event.srcElement;
	
	switch (event.keyCode){
	case 16:
		// register shift keydown
		
		holding_key = 16;
		break;
	case 13:
		// enter
		
		if(event.preventDefault) 
			event.preventDefault();
		else
			event.returnValue = false;
		
		// submit msg to the server
		var msg = $.trim(target.value);
		if (msg != ""){
			$.ajax({
				url:	"/ajaxDiscussion",
				type: 	"post",
				data:	{action: 'msg_add', discussion_id: discussionId, message: msg, activity_assoc: actAssocId},
				success: function(result){
					if (result == 1){
						// successful request 
						
						target.value = "";
					}
				}
			});
		}
		
		break;
	}
}

var discussion_msg_keyup = function(event, discussionId){
	// IE8 fix
	event = event || window.event;
	var target = event.target || event.srcElement;
	
	if (event.keyCode == 13 && holding_key == 16){
		// shift+enter
		target.value = target.value + "\n";
	}
	
	// reset holding
	holding_key = "";
}

var showParticipants = function(discId){
	var count = $("#participants_summary_"+discId+" li").length;
	if (count > 0){
		$("#participants_summary_"+discId).show();
	}
}

var hideParticipants = function(discId){
	$("#participants_summary_"+discId).hide();
}


/* loads when document finishes loading */
$(document).ready(function () {
	// remove dropdowns when user clicks on another part of the window
	$(document).click(function (e) {
		// activity details dropdown
		if ($(e.target).closest('[id^=activity_drop_]').length <= 0){
			$("[id^=activity_edit_]").hide();
		}
		
		// discussion new dropdown
		if ($(e.target).closest('[id^=discussion_tab_add_]').length <= 0) {
			$("[id^=discussion_new_]").hide();
		}
    });
	
	// timer for continuously updating the chat messages
	var chatUpdateInterval = setInterval(function(){
		// first check how many discussion_main_XX elements are there
		var arrDiscMain = $("[id^=message_container_]");
		if (arrDiscMain.length>0){
			for (var i=0; i<arrDiscMain.length; i++){
				// message display area
				var msgObj = arrDiscMain[i];
				var discussionId = msgObj.id.split('_')[2];
				
				// send ajax request
				$.ajax({
					url:	"/ajaxDiscussion",
					type: 	"post",
					data:	{action: 'discussion_refresh', discussion_id: discussionId},
					async:	true,
					timeout: 3000,
					success: function(result){
						if (result != ""){
							if (result == -1){
								// stop
								clearInterval(chatUpdateInterval);
							} else {
								// successful request 
	
								msgObj.innerHTML = result;
								msgObj.scrollTop = msgObj.scrollHeight;
							}
						}
					},
					error: function(){
						// stop
						clearInterval(chatUpdateInterval);
					}
				});
			}
		}
		
	}, 200);
});