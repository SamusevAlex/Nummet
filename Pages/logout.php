<?php
setcookie("login");
setcookie("password");
header("Location:{$_SERVER['HTTP_REFERER']}");
