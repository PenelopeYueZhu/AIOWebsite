<?php
session_start();
session_destroy();

// Go back to the previous page
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
 ?>