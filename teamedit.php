<?php
include "functions.php";
$con = dbconnection::getDbConnection(conf::getParam("dbhost"),conf::getParam("dbuser"),conf::getParam("dbpass"),conf::getParam("db"));
$azubidata = getAzubiData($con);
$bigid = getBiggestAzubiID($azubidata);

if (!empty(getRequestParameter("delete"))){
    $azubi = new azubi();
    $azubi->delete(getRequestParameter("delete"));
    getLocationStringAndRedirect(1,getRequestParameter("dropdown"),getRequestParameter("lastsearch"),getRequestParameter("order"),getRequestParameter("orderdir"));
}
if (!empty(getRequestParameter("newazubi"))){
    header("location: ".getUrl("inputsite.php"));
}
if (getRequestParameter("deletearray") !== false) {
    foreach (getRequestParameter("deletearray") as $id){
        $azubi = new azubi();
        $azubi->delete($id);
    }
    getLocationStringAndRedirect(getRequestParameter("page"),getRequestParameter("dropdown"),getRequestParameter("lastsearch"),getRequestParameter("order"),getRequestParameter("orderdir"));
}
if (!empty(getRequestParameter("submitsearch"))){
    getLocationStringAndRedirect(1,getRequestParameter("dropdown"),getRequestParameter("search"),getRequestParameter("order"),getRequestParameter("orderdir"));
}
if (!empty(getRequestParameter("ddsubmit"))){
    getLocationStringAndRedirect(1,getRequestParameter("dropdown"),getRequestParameter("lastsearch"),getRequestParameter("order"),getRequestParameter("orderdir"));
}
if (!empty(getRequestParameter("pageselect"))){
    getLocationStringAndRedirect(getRequestParameter("pageselect"),getRequestParameter("dropdown"),getRequestParameter("lastsearch"),getRequestParameter("order"),getRequestParameter("orderdir"));
}