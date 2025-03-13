
window.onload=function(){
	var lbs = document.getElementsByTagName('label');
	for(var i=0;i<lbs.length;i++){
		var cimgs = lbs[i].getElementsByTagName('img');
			for(var j=0;j<cimgs.length;j++){
				cimgs[j].formCtrlId = lbs[i].htmlFor;
				cimgs[j].onclick = function(){document.getElementById(this.formCtrlId).click()};
			}
	}
}
