<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reset Password</title>
</head>

<body>
<form name="resetPasswordForm" action="http://www.chrisrocco.net/bigdataed/API/public/index.php/reset" method="post" onSubmit="return validate()">
  <label>New Password: </label>
  <input name="newPassword" type="text">
  <br/>
  <label>Confirm New Password: </label>
  <input name="confirmNewPassword" type="text">
  <br/>
  <input type="submit">
  
  <input name="hash" type="hidden" value="<?=$_GET['hash']?>">
</form>
<script>
function validate(){
	var newPassword = document.forms['resetPasswordForm']['newPassword'].value;
	var confirmNewPassword = document.forms['resetPasswordForm']['confirmNewPassword'].value;
	
	if(newPassword.length < 7){ alert("password must be at least 7 characters"); return false; }
	if(newPassword !== confirmNewPassword){ alert("passwords do not match"); return false; }
	
	return true;
}
</script>
<?php include_once("js/analyticstracking.php") ?>
</body>
</html>