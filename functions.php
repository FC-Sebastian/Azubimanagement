<?php
include "classes/conf.php";
include "classes/dbconnection.php";
include "classes/azubi.php";

function getAzubiData($connection, $azubiid = false,$needle = "*", $orderby = false, $orderdir = 0,$search = false, $limit = false, $offset = 0)
{
    $query = "SELECT $needle FROM azubi";
    if (!empty($search)){
        $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
    }
    if ($azubiid !== false && !empty($search)) {
        $query .= " AND id = '$azubiid'";
    } elseif ($azubiid !== false){
        $query .= " WHERE id='$azubiid'";
    }
    if (!empty($orderby)){
        $query .= " ORDER BY ".$orderby;
    }
    if ($orderdir < 0){
        $query .= " DESC";
    }
    if ($limit !== false){
        $query .= " LIMIT ".$offset.",".$limit;
    }
    $query .= ";";
    $result = executeMySQLQuery($connection,$query);
    $array = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $objectarray = [];
    foreach ($array as $azu){
        $azubi = new azubi(
            $azu["id"],
            $azu["name"],
            $azu["birthday"],
            $azu["email"],
            $azu["githubuser"],
            $azu["employmentstart"],
            $azu["pictureurl"],
            $azu["password"]);
        $objectarray[] = $azubi;
    }
    return $objectarray;
}
function getLocationStringAndRedirect($page = false,$dropdown = false,$search = false,$order = false,$orderdirection = false)
{
    $locationstring = "location: ".conf::getParam("url")."teameditsite.php?";
    if (false !== $page){
        $locationstring .= "page=".$page;
    }
    if (false !== $dropdown){
        $locationstring .= "&dropdown=".$dropdown;
    }
    if (false !== $search){
        $locationstring .= "&search=".$search;
    }
    if (false !== $order){
        $locationstring .= "&order=".$order."&orderdir=".$orderdirection;
    }
    header($locationstring);
}
function getPageMax($limit,$azubidata)
{
    $allazubicount = count($azubidata);
    return  ceil($allazubicount / $limit);
}
function getPictureUrl($filename)
{
    if (!empty($filename)) {
        return conf::getParam("url")."pics/".$filename;
    }
    return "https://secure.gravatar.com/avatar/cb665e6a65789619c27932fc7b51f8dc?default=mm&size=200&rating=G";
}
function getRequestParameter($key,$default = false)
{
    if (isset($_REQUEST[$key])){
        return $_REQUEST[$key];
    }
    return $default;
}
function getUrl($sitename = "")
{
    return conf::getParam("url").$sitename;
}
function getValueIfIsset($array,$key){
    if (isset($array[0][$key])){
        return  $array[0][$key];
    }
    return false;
}
function addSaltGetMD5($password)
{
    return md5(conf::getParam("salt").$password);
}
function executeMySQLQuery($connection,$query)
{
    $result = mysqli_query($connection,$query);
    $error = mysqli_error($connection);
    if (!empty($error)){
        echo "<h1>Error with query: ".$query." <br> Error:".$error."</h1>";
    }
    #echo $query."<br>";
    return $result;
}
function saveAzubi($password)
{
    $azubi = new azubi(
        getRequestParameter("id"),
        getRequestParameter("name"),
        getRequestParameter("birthday"),
        getRequestParameter("email"),
        getRequestParameter("githubuser"),
        getRequestParameter("employmentstart"),
        uploadPictureGetFilename(),
        $password,
        explode(",",getRequestParameter("kskills")),
        explode(",",getRequestParameter("nskills")));
    $azubi->save();
}
function timeSince($input)
{
    $date = ((time() - strtotime($input)) / 60 / 60 / 24);
    $yrs = 0;
    $mnths = 0;
    $days = 0;
    $output = "";
    while($date >= 1) {
        if ($date / 365 >= 1) {
            $date -= 365;
            $yrs += 1;
        } elseif ($date / 30 >= 1) {
            $date -= 30;
            $mnths += 1;
        } elseif ($date >= 1) {
            $date -= 1;
            $days += 1;
        }
    }
    if ($days > 0) {
        $output .= "Bei Fatchip angestellt seit ".$days." Tagen";
    }
    if ($mnths > 0){
        $output .= ", ".$mnths." Monaten";
    }
    if ($yrs > 0){
        $output .= " und ".$yrs." Jahren";
    }
    return $output;
}
function uploadPictureGetFilename ()
{
    $tmppath = $_FILES["pictureurl"]["tmp_name"];
    $name = $_FILES["pictureurl"]["name"];
    $type = $_FILES["pictureurl"]["type"];
    $locationbegin = __DIR__.'/pics/';
    $location = $locationbegin . $name;
    $allowed = ["image/jpeg","image/png","image/gif","image/svg+xml","image/jpg","	image/webp"];
    if (in_array($type,$allowed)){
        move_uploaded_file($tmppath, $location);
        return $name;
    }
    return null;
}
function validateAzubiLogin($connection,$email,$password)
{
    if (!empty($email)){
        $query = "SELECT email, password FROM azubi WHERE email = '$email' AND password = '$password'";

        $result = executeMySQLQuery($connection,$query);
        $azubilogin = mysqli_fetch_all($result,MYSQLI_ASSOC);
        if (!empty($azubilogin)){
            return true;
        }
    }
}