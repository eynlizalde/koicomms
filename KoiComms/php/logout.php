<?php
session_start();
session_unset();
session_destroy();
header("Location: ../components/homepage.php");
exit();
?>