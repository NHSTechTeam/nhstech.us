<html>
<head>
    <title>Tech Productions - Member</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
</head>
<body>
<?php
require("api/api.php");
session_start();
$user = get_user($_GET["id"]);
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<div class="navbar-inverse navbar navbar-static-top">
    <div class="container">
        <a href="/" class="navbar-brand">Productions</a>
        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="/">Home</a></li>
                <?php if (in_array(1, $_SESSION["perms"]) || in_array(2, $_SESSION["perms"]) || in_array(3, $_SESSION["perms"])) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Add <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php if (in_array(1, $_SESSION["perms"])) { ?>
                                <li><a href="#add_user" data-toggle="modal">Member</a></li>
                            <?php
                            }
                            if (in_array(2, $_SESSION["perms"])) {
                                ?>
                                <li><a href="#add_production" data-toggle="modal">Production</a></li>
                            <?php
                            }
                            if (in_array(3, $_SESSION["perms"])) {
                                ?>
                                <li><a href="#add_editing" data-toggle="modal">Editing</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li><a href="<?php
                    if (is_null($_SESSION["perms"])) {
                        echo '#login';
                    } else {
                        echo 'api/logout.php';
                    } ?>" data-toggle="modal"><?php
                        if (is_null($_SESSION["perms"])) {
                            echo 'Login';
                        } else {
                            echo 'Logout';
                        } ?></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="jumbotron">
        <h1>Member:</h1>
        <h2><?php echo $user["first_name"] . ' ' . $user["last_name"] ?></h2>
    </div>
    <?php if (in_array(6, $_SESSION["perms"]) || in_array(7, $_SESSION["perms"])|| in_array(8, $_SESSION["perms"])) { ?>
        <div class="col-lg-12">
            <div class="panel panel-warning">
                <div class="panel-heading">Manage</div>
                <div class="panel-body">
                    <div class="form-inline">
                        <?php if (in_array(8, $_SESSION["perms"])) {?>
                            <a class="btn btn-warning" href="api/reset_password.php?id=<?php echo $_GET["id"] ?>">Reset Password</a>
                        <?php }
                        if (in_array(6, $_SESSION["perms"])) { ?>
                            <a class="btn btn-danger" href="api/delete.php?id=<?php echo $_GET["id"] ?>">Delete</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    if (in_array(4, $_SESSION["perms"])) {
        ?>
        <label for="productions" class="control-label"> Productions:</label>
        <table class="table table-bordered" id="productions">
            <tr>
                <th>Name</th>
                <th>Month</th>
                <th>Day</th>
                <th>Hours</th>
            </tr>
            <?php foreach (get_member_productions($_GET["id"]) as $production) { ?>
                <tr>
                    <td><?php echo $production["p_name"] ?></td>
                    <td><?php echo $production["p_month"] ?></td>
                    <td><?php echo $production["p_day"] ?></td>
                    <td><?php echo $production["p_hours"] ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php
    }
    if (in_array(4, $_SESSION["perms"])) {
        ?>
        <label for="editting" class="control-label">Editting:</label>
        <table class="table table-bordered" id="editting">
            <tr>
                <th>Name</th>
                <th>Month</th>
                <th>Day</th>
                <th>Hours</th>
            </tr>
            <?php foreach (get_editing($_GET["id"]) as $edit) { ?>
                <tr>
                    <td><?php echo $edit["e_name"] ?></td>
                    <td><?php echo $edit["e_month"] ?></td>
                    <td><?php echo $edit["e_day"] ?></td>
                    <td><?php echo $edit["e_hours"] ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>
<div class="modal fade" id="add_user" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="api/register.php">
                <div class="modal-header">
                    <h4>Add User</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="first_name" class="col-lg-2 control-label">First:</label>
                        <div class="col-lg-10">
                            <input name="first_name" type="text" class="form-control" id="first_name" placeholder="Joe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-lg-2 control-label">Last:</label>
                        <div class="col-lg-10">
                            <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Shmoe">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Add</button>
                    <a class="btn btn-default" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="login" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="api/login.php">
                <div class="modal-header">
                    <h4>Login</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id" class="col-lg-2 control-label">Name:</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="id" name="id">
                                <?php
                                $members = get_users(true);
                                while ($member = $members->fetch_assoc()) {
                                    ?>
                                    <option
                                        value="<?php echo $member["m_id"] ?>"><?php echo $member["first_name"] . ' ' . $member["last_name"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Password:</label>
                        <div class="col-lg-10">
                            <input name="password" type="password" class="form-control" id="password"
                                   placeholder="********">
                        </div>
                    </div>
                </div>
                <input name="location" type="hidden" value="member.php?id=<?php echo $_GET["id"] ?>">
                <div class="modal-footer">
                    <a class="pull-left btn btn-warning" data-dismiss="modal" data-toggle="modal" href="#register_password">Add
                        Password</a>
                    <button class="btn btn-primary" type="submit">Login</button>
                    <a class="btn btn-default" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="register_password" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="api/add_password.php">
                <div class="modal-header">
                    <h4>Add Password</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name:</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="name" name="name">
                                <?php
                                $members = get_users(true);
                                while ($member = $members->fetch_assoc()) {
                                    ?>
                                    <option
                                        value="<?php echo $member["m_id"] ?>"><?php echo $member["first_name"] . ' ' . $member["last_name"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Password:</label>
                        <div class="col-lg-10">
                            <input value="poop" class="form-control" type="password" id="password" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password" class="col-lg-2 control-label">Confirm Password:</label>
                        <div class="col-lg-10">
                            <input value="poop" class="form-control" type="password" id="confirm-password" name="confirm-password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn-danger btn" data-dismiss="modal">Close</a>
                    <button class="btn btn-primary" type="submit">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="add_production" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="api/add_production.php">
                <div class="modal-header">
                    <h4>Add Production</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" id="name" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="month" class="col-lg-2 control-label">Month:</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="month" id="month">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="day" class="col-lg-2 control-label">Day:</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="day" id="day">
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hours" class="col-lg-2 control-label">Hours:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" name="hours" id="hours">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paid" class="col-lg-2 control-label">Paid:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="checkbox" name="paid" id="paid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attendants" class="col-lg-2 control-label">Attendants:</label>
                        <div class="col-lg-10">
                            <select multiple class="form-control" id="attendants" name="attendants[]">
                                <?php
                                $members = get_users(true);
                                while ($member = $members->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $member[" m_id
                                "] ?>"><?php echo $member["first_name"] . ' ' . $member["last_name"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Add</button>
                    <a class="btn btn-default" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="add_editing" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="api/add_editing.php">
                <div class="modal-header">
                    <h4>Add Editing</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" id="name" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="month" class="col-lg-2 control-label">Month:</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="month" id="month">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="day" class="col-lg-2 control-label">Day:</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="day" id="day">
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hours" class="col-lg-2 control-label">Hours:</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" name="hours" id="hours">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="attendant" class="col-lg-2 control-label">Editor:</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="attendant" name="attendant">
                                <?php
                                $members = get_users(true);
                                while ($member = $members->fetch_assoc()) {
                                    ?>
                                    <option
                                        value="<?php echo $member[" m_id
                                "] ?>"><?php echo $member["first_name"] . ' ' . $member["last_name"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Add</button>
                    <a class="btn btn-default" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>