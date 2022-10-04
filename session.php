<?php
session_start();
if (!isset($_SESSION["logintime"])){
    header("location: http://localhost/loginsite.php");
}
if (isset($_SESSION["logintime"])){
    if ((time() - $_SESSION["logintime"]) >= 300){
        session_unset();
        session_destroy();
    }
}
