<?php
require_once '../config/session.php';
destroySession();
header("Location: ../index.php");
exit();
?>