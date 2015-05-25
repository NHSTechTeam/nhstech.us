<?php

function get_mysql()
{
    $mysql = new mysqli("160.153.34.72", "tech", "superce11", "tech_team"); // TODO: Remove password!
    if ($mysql->connect_error) {
        die($mysql->connect_error);
    }
    return $mysql;
}

function get_users()
{
    return get_mysql()->query("SELECT * FROM members ORDER BY first_name");
}

function get_user($id)
{
    $result = get_mysql()->query("SELECT first_name, last_name FROM members WHERE m_id=$id LIMIT 1")->fetch_assoc();
    return $result;
}

function get_total_hours($member_id)
{
    $mysql = get_mysql();
    $hours = 0;
    $productions = $mysql->query("SELECT p_id FROM production_attendants WHERE m_id=$member_id");
    while ($production = $productions->fetch_assoc()) {
        $hours += $mysql->query("SELECT p_hours FROM productions WHERE p_id=" . $production["p_id"])->fetch_assoc()["p_hours"];
    }
    $productions = $mysql->query("SELECT e_hours FROM editing WHERE m_id=$member_id");
    while ($production = $productions->fetch_assoc()) {
        $hours += $production["e_hours"];
    }
    return $hours;
}

function get_hours_in_month($member_id, $month)
{
    $mysql = get_mysql();
    $hours = 0;
    $productions = $mysql->query("SELECT p_id FROM production_attendants WHERE m_id=$member_id");
    while ($production = $productions->fetch_assoc()) {
        $hours += $mysql->query("SELECT p_hours FROM productions WHERE p_id=" . $production["p_id"] . " AND p_month=$month");
    }
    $productions = $mysql->query("SELECT e_hours FROM editing WHERE m_id=$member_id AND e_month=$month");
    while ($production = $productions->fetch_assoc()) {
        $hours += $production["e_hours"];
    }
    return $hours;
}

function get_member_productions($member_id)
{
    $mysql = get_mysql();
    $productions = $mysql->query("SELECT p_id FROM production_attendants WHERE m_id=$member_id");
    $productions_array = array();
    while ($production = $productions->fetch_assoc()) {
        $query = "SELECT * FROM productions WHERE p_id=" . $production["p_id"] . " LIMIT 1";
        array_push($productions_array, $mysql->query($query)->fetch_assoc());
    }
    return $productions_array;
}

function get_editing($member_id)
{
    $editing = get_mysql()->query("SELECT * FROM editing WHERE m_id=$member_id");
    $editing_array = array();
    while ($edit = $editing->fetch_assoc()) {
        array_push($editing_array, $edit);
    }
    return $editing_array;
}

function add_production($name, $month, $day, $hours, $paid, $attendants)
{
    $mysql = get_mysql();
    $result = $mysql->query("SELECT p_id FROM productions ORDER BY p_id DESC LIMIT 1");
    $data = $result->fetch_assoc()["p_id"] + 1;
    $query = "INSERT INTO productions (p_id, p_name, p_month, p_day, p_paid, p_hours) VALUES ($data, '$name', $month, $day, $paid, $hours);";
    $mysql->query($query);
    foreach ($attendants as $attendant) {
        $mysql->query("INSERT INTO production_attendants (p_id, m_id) VALUES ($data, $attendant)");
    }
}

function add_editing($name, $month, $day, $hours, $attendant)
{
    get_mysql()->query("INSERT INTO editing (e_name, e_month, e_day, e_hours, m_id) VALUES ('$name', $month, $day, $hours, $attendant);");
}

function register_user($first, $last)
{
    $id = (ord(substr($first, 0, 1)) * 1000000000) + (ord(substr($last, 0, 1)) * 1000000) + (ord(substr($last, 1, 2)) * 1000) + (ord(substr($last, 2, 3)));
    $query = "INSERT INTO members (m_id, first_name, last_name) values ($id, '$first', '$last')";
    get_mysql()->query($query);
}

function login($member_id, $password)
{
    $mysql = get_mysql();
    $password = hash("sha256", $password);
    $check = $mysql->query("SELECT m_password FROM members WHERE m_id=$member_id")->fetch_assoc()["m_password"];
    if (!is_null($check) && $password == $check) {
        return true;
    }
    return false;
}

function get_permissions($member_id)
{
    $mysql = get_mysql();
    $perms = array();
    $query = $mysql->query("SELECT p_id FROM member_perms WHERE m_id=$member_id");
    while ($perm = $query->fetch_assoc()) {
        array_push($perms, $perm["p_id"]);
    }
    return $perms;
}

function reset_password($member_id)
{
    $mysql = get_mysql();
    $mysql->query("UPDATE members SET m_password=NULL WHERE m_id=$member_id");
}

function delete($member_id)
{
    $mysql = get_mysql();
    $mysql->query("DELETE FROM members WHERE m_id=$member_id");
}