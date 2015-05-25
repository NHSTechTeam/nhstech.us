<?php
require("api.php");
if (isset($_POST["name"]) && isset($_POST["month"]) && isset($_POST["day"]) && isset($_POST["hours"]) && isset($_POST["attendants"])) {
    $paid = 0;
    if ($_POST["paid"] == 'on') {
        $paid = 1;
    }
    add_production($_POST["name"], $_POST["month"], $_POST["day"], $_POST["hours"], $paid, $_POST["attendants"]);
}
header("Location: ../" . $_POST["location"]);