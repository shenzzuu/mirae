<?php
session_start();
session_unset();  // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to homepage
header("Location: index.html");
exit();
?>
