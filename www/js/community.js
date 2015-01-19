var likeActivity = function (event, activityAssocId, friendId){
	ele = event.target;
	event.stopPropagation();
	
	if (!ele.type){
		ele.type = "onboard";
	}
	
	$.ajax({
		url:	"ajaxActivityAssociation",
		type: 	"post",
		data:	{activity_assoc: activityAssocId, friend_id: friendId, action: ele.type},
		success: function(result){
			if (result!=""){
				// successful request 
				
				// change interest tally
				intTallyEl = $("#interest_tally_"+activityAssocId);
				if (intTallyEl.length>0) {
					intTallyEl.html(result + " interests");
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