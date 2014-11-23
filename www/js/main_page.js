var displayDetailsBox = function (div_id){
	var div = document.getElementById(div_id);
	if (div){
		if (div.style.display=="block")
			div.style.display = "none";
		else
			div.style.display = "block";
	}
}