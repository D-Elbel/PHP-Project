<?php include 'header.php';

session_unset();
session_destroy();

echo "<h2>You have been logged out, click <a href='signup.php'>here</a> to sign in!</h2>";

include 'footer.php';
