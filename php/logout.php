<?php
session_start();
unset($_SESSION['ID']);
unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['role']);
unset($_SESSION['login_time']);
echo "<script>window.location = '../index.php'</script>";