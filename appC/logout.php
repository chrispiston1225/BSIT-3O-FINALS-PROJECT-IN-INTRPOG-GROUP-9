<?php
session_unset();
session_destroy();

// Delete Remember Me cookies if set
if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, '/');
}
if (isset($_COOKIE['user_token'])) {
    setcookie('user_token', '', time() - 3600, '/');
}

header('Location: login.php');
exit();
?>
