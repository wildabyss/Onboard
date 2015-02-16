var likeActivity = function (event, activityAssocId, friendId){
	// IE8 fix
	event = event || window.event;
	var ele = event.target || event.srcElement;
	if (event.stopPropagation)
		event.stopPropagation();
	else
		event.cancelBubble = true;
	
	// default type
	if (!ele.type){
		ele.type = "onboard";
	}
	
	$.ajax({
		url:	"/ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, friend_id: friendId, action: ele.type},
		success: function(result){
			if (result!=""){
				// successful request 
				
				var respArr = JSON.parse(result);
				
				// change the title appearance (link or no link)
				
				if (respArr.assoc_id > 0){
					$('#activity_title_'+activityAssocId).parent().attr('href', '/id/'+respArr.user_id+'#activity_section_'+respArr.assoc_id);
				} else {
					$('#activity_title_'+activityAssocId).parent().removeAttr('href');
				}
				
				// change interest tally
				var intTallyEl = $("#interest_tally_"+activityAssocId);
				if (intTallyEl.length>0) {
					intTallyEl.html(respArr.interest_count + " interests");
				}
				
				// change button
				if (ele.type == "onboard"){
					ele.type = "leave";
					ele.innerHTML = "Leave";
				} else {
					ele.type = "onboard";
					ele.innerHTML = "Onboard!";
				}
			}
		}
	});
	
};