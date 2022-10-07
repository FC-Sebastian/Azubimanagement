<?php

include "functions.php";
if (getRequestParameter("pass") === getRequestParameter("confpass") && getRequestParameter("pass") !== false) {
    $password = addSaltGetMD5(getRequestParameter("pass"));
} else {
    header("location: " . getUrl("inputsite.php") . "?passmismatch=1&id=" . getRequestParameter("id"));
    exit();
}
if (!empty(getRequestParameter("delete")) && getRequestParameter("delete") == "on") {
    $azubi = new azubi(getRequestParameter("id"));
    $azubi->delete();
    header("location: " . getUrl("inputsite.php"));
} elseif (!empty(getRequestParameter("id"))) {
    saveAzubi($password);
    header("location: " . getUrl("inputsite.php"));
} else {
    saveAzubi($password);
    header("location: " . getUrl("inputsite.php"));
}
