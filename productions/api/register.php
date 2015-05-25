<?php

require("api.php");
register_user($_POST["first_name"], $_POST["last_name"]);
header("Location: ../" . $_POST["location"]);