<?php

require("api.php");
session_start();
if (!is_null($_SESSION["perms"])) {
    session_destroy();
}
header("Location: ../");
