<?php
include "functions.php";
$con = getDatabaseConnection();
$azubidata = getAzubiData($con);
$id = getAzubiID($azubidata);
$pararray = ["name","birthday","email",
    "githubuser","githubuserlink",
    "employmentstart", "pictureurl","password"];
if (getRequestParameter("pass") === getRequestParameter("confpass") && getRequestParameter("pass") !== false){
    $password = addSaltGetMD5(getRequestParameter("pass"));
} else {
    header("location: http://localhost/inputsite.php?passmismatch=1&id=".getRequestParameter("id"));
    exit();
}
if (!empty(getRequestParameter("delete")) && getRequestParameter("delete") == "on"){
    deleteAzubi($con,getRequestParameter("id"));
    header("location: inputsite.php");
} elseif (!empty(getRequestParameter("id"))) {
    echo $password."<br>";
    updateAzubi($pararray,getRequestParameter("id"),getRequestParameter("kskills"),getRequestParameter("nskills"),$con,$password);
    header("location: inputsite.php");
} else {
    insertAzubi($pararray,$id,getRequestParameter("kskills"),getRequestParameter("nskills"),$con,$password);
    header("location: inputsite.php");
}
mysqli_close($con);
