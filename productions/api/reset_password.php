<?php

if (isset($_GET["id"])) {
    reset_password($_GET["id"]);
}
header("Location: ../");