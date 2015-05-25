<?php

require("api.php");
session_start();
if (isset($_POST["id"]) && isset($_POST["password"])) {
    if (login($_POST["id"], $_POST["password"])) {
        $_SESSION["perms"] = array();
        foreach (get_permissions($_POST["id"]) as $perm) {
            array_push($_SESSION["perms"], $perm);
        }
        $_SESSION["id"] = $_POST["id"];
    }
}
header("Location: ../" . $_POST["location"]);