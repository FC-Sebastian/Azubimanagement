<?php
include "config.php";
include "functions.php";
$con = dbconnection::getDbConnection(conf::getParam("dbhost"),conf::getParam("dbuser"),conf::getParam("dbpass"),conf::getParam("db"));
$azubidata = getAzubiData($con);
$id = getAzubiID($azubidata);
$pararray = ["name", "birthday", "email",
    "githubuser", "employmentstart", "pictureurl", "password"];
if (getRequestParameter("pass") === getRequestParameter("confpass") && getRequestParameter("pass") !== false){
    $password = addSaltGetMD5(getRequestParameter("pass"));
} else {
    header("location: ".getUrl("inputsite.php")."?passmismatch=1&id=".getRequestParameter("id"));
    exit();
}
if (!empty(getRequestParameter("delete")) && getRequestParameter("delete") == "on"){
    $azubi = new azubi(getRequestParameter("id"));
    $azubi -> delete();
    header("location: ".getUrl("inputsite.php"));
} elseif (!empty(getRequestParameter("id"))) {
    saveAzubi($password);
    header("location: ".getUrl("inputsite.php"));
} else {
    saveAzubi($password);
    header("location: ".getUrl("inputsite.php"));
}
mysqli_close($con);
