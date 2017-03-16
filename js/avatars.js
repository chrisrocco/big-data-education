$(document).ready(function(){
	loadAvatar();
});
function loadAvatar(){
	if(localStorage.avatar){
		var avatarImgs = document.getElementsByName("avatar");
		$(avatarImgs).attr("src", localStorage.avatar);
	}
}
function setAvatar(uri){
	localStorage.avatar = uri;
	loadAvatar();
}