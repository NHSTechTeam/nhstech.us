<?php
if (isset($_GET["id"])) {
    delete($_GET["id"]);
}
header("Location: ../");