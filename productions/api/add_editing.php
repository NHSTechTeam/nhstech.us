<?php
require("api.php");
if (isset($_POST["name"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["hours"]) && isset($_POST["attendant"])) {
    add_editing($_POST["name"], $_POST["month"], $_POST["day"], $_POST["hours"], $_POST["attendant"]);
}
header("Location: ../" . $_POST["location"]);