var likeActivity = function (ele, activityId){
	$.ajax({
		url:	"ajaxLike",
		type: 	"post",
		success: function(result){
			document.getElementById("interest_tally_"+activityId).innerHTML = "1000 interests";
			
			ele.innerHTML="Leave";
		}
	});
	
};