<?php
session_start();
session_destroy();

header("Location: ../../../../client/index.php");
die();