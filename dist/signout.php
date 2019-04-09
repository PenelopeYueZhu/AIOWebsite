<?php
/**
 * Script to sign the user (Admin) out.
 * Uses google
 */
session_start();
session_unset();
session_destroy();

// Go back to the previous page
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
 ?>
