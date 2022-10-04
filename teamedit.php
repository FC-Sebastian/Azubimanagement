<?php
include "functions.php";
$con = getDatabaseConnection();
$azubidata = getAzubiData($con,false,false,0);
$bigid = getBiggestAzubiID($azubidata);

if (!empty(getRequestParameter("delete"))){
    deleteAzubi($con,getRequestParameter("delete"));
    getLocationStringAndRedirect(1,getRequestParameter("dropdown"),getRequestParameter("lastsearch"),getRequestParameter("order"),getRequestParameter("orderdir"));
}
if (!empty(getRequestParameter("newazubi"))){
    header("location: inputsite.php");
}
if (false !== (getRequestParameter("deletearray"))) {
    foreach (getRequestParameter("deletearray") as $id){
        deleteAzubi($con,$id);
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