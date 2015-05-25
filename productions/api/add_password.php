<?php
require("api.php");
if (isset($_POST["password"]) && isset($_POST["confirm-password"])) {
    if ($_POST["password"] != $_POST["confirm-password"]) {
        header('Location: ../index.php?pwd=1');
    } else {
        $mysql = get_mysql();
        $id = $_POST["name"];
        $query = "SELECT m_password FROM members WHERE m_id=$id";
        if (!is_null($mysql->query($query)->fetch_assoc()["m_password"])) {
            header('Location: ../index.php?pwd=2');
        } else {
            $password = hash("sha256", $_POST["password"]);
            $mysql->query("UPDATE members SET m_password='$password' WHERE m_id=$id");
        }
    }
} else {
    header('Location: ../index.php?pwd=3');
}