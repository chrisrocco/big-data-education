<?php
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$data = array('email' => $email, 'password' => $password);

$result = $DataService->post("accounts/login", $data);
if ($result === FALSE) {
	/* TODO - handle error */
	echo "<script>window.location = 'index.php'</script>";
}
if ($result == 'invalid'){ 
	echo "<script>window.location = 'index.php'</script>";
}
if ($result == 'approved'){
	/* Do nothing */
}
?>