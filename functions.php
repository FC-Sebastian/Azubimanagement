<?php
function getDatabaseConnection()
{
    $sqlarray=getConfigParameter("MySQL");
    $connection = mysqli_connect($sqlarray["hostname"], $sqlarray["username"],$sqlarray["password"],$sqlarray["database"]);
    if (mysqli_connect_errno()) {
        echo "fail ".mysqli_connect_error();
        exit();
    }
    return $connection;
}

function getRequestParameter($key,$default = false)
{
    if (isset($_REQUEST[$key])){
        return $_REQUEST[$key];
    }
    return $default;
}

function getPictureUrl($filename)
{
    if (!empty($filename)) {
        return getConfigParameter("url")."pics/".$filename;
    }
    return "https://secure.gravatar.com/avatar/cb665e6a65789619c27932fc7b51f8dc?default=mm&size=200&rating=G";
}

function getSkillsByType($connection, $azubiid, $type)
{
    $result = executeMySQLQuery($connection,"SELECT skill FROM azubi_skills WHERE azubi_id = $azubiid AND type = '$type'");
    return mysqli_fetch_all($result,MYSQLI_ASSOC);
}

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
    return mysqli_fetch_all($result,MYSQLI_ASSOC);
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

function getInsertQuery($array,$azubiid,$password)
{
    $querybegin = "INSERT INTO azubi (id";
    $queryend = ") VALUES ( '" .$azubiid. "'";
    foreach ($array as $key){
        if ($key == "pictureurl"){
            if (!empty(uploadPictureGetFilename())){
                $querybegin .= ", ".$key;
                $queryend .= ", '".uploadPictureGetFilename()."'";
            }
        } elseif ($key == "password"){
            $querybegin .= ", ".$key;
            $queryend .= ", '".$password."'";
        } elseif (!empty(getRequestParameter($key))){
            $querybegin .= ", ".$key;
            $queryend .= ", '".getRequestParameter($key)."'";
        }
    }
    $query = $querybegin.$queryend.")";
    return $query;
}

function skillInsert($skillarray,$connection,$azubiid,$skilltype)
{
    $oldskills = getSkillArray($connection,$azubiid,$skilltype);
    $i = 0;
    foreach ($skillarray as $skill){
        if (!empty($skill) && $oldskills[$i] != $skill){
            $skillquery = "INSERT INTO azubi_skills (azubi_id,type,skill) VALUES (";
            $skillquery .= "'".$azubiid."', '".$skilltype."', '".trim($skill)."')";
            executeMySQLQuery($connection, $skillquery);
        }
        $i++;
    }
}

function getValueIfIsset($array,$parameter){
    if(isset($array[$parameter])){
        return $array[$parameter];
    }
}

function getSkillArray($connection, $azubiid, $type){
    $skills = getSkillsByType($connection, $azubiid, $type);
    $skillarray = [];
    foreach ($skills as $sks){
        $skillarray[]=$sks["skill"];
    }
    return $skillarray;
}

function getUpdateQuery($array,$azubiid,$password)
{
    $querybegin = "UPDATE azubi ";
    $querymid = "SET ";
    $queryend = "WHERE id = ".$azubiid;
    foreach ($array as $key){
        if ($key == "pictureurl"){
            if (!empty(uploadPictureGetFilename())){
                $querymid .= $key."="."'".uploadPictureGetFilename()."',";
            }
        } elseif ($key == "password"){
            $querymid .= $key."="."'".$password."',";
        } elseif (!empty(getRequestParameter($key))) {
            $querymid .= $key."="."'".getRequestParameter($key)."',";
        }
    }
    $query=$querybegin.substr($querymid,0,-1).$queryend;
    return $query;
}

function skillUpdate($skillarray,$connection,$azubiid,$skilltype)
{
    $oldskills = getSkillArray($connection,$azubiid,$skilltype);
    $i = 0;
    foreach ($oldskills as $skill){
        if ($skillarray[$i] !== $skill) {
            $skillquery = "DELETE FROM azubi_skills WHERE azubi_id ='".$azubiid."' AND type = '".$skilltype."' AND skill = '".$oldskills[$i]."'";
            executeMySQLQuery($connection, $skillquery);
        }
        $i++;
    }
    skillInsert($skillarray,$connection,$azubiid,$skilltype);
}

function deleteAzubi($connection,$id)
{
    $azubi_query = "DELETE FROM azubi WHERE id='".$id."'";
    $skill_query = "DELETE FROM azubi_skills WHERE azubi_id='".$id."'";
    if (executeMySQLQuery($connection, $azubi_query)) {
        executeMySQLQuery($connection,$skill_query);
    } else {
        echo "Error: " . $azubi_query . "<br><br>" . mysqli_error($connection);
    }
}

function updateAzubi($parameterarray,$id,$kskills,$nskills,$connection,$password)
{
    $azubi_query = getUpdateQuery($parameterarray,$id,$password);
    $preskills = explode(", ",$kskills);
    $newskills = explode(", ",$nskills);
    if (executeMySQLQuery($connection, $azubi_query)) {
        skillUpdate($preskills,$connection,$id,"pre");
        skillUpdate($newskills,$connection,$id,"new");
    } else {
        echo "Error: " . $azubi_query . "<br><br>" . mysqli_error($connection);
    }
}

function insertAzubi($parameterarray,$id,$kskills,$nskills,$connection,$password)
{
    $preskills = explode(",",$kskills);
    $newskills = explode(",",$nskills);
    $azubi_query = getInsertQuery($parameterarray,$id,$password);
    if (executeMySQLQuery($connection, $azubi_query)) {
        skillInsert($preskills,$connection,$id,"pre");
        skillInsert($newskills,$connection,$id,"new");
    } else {
        echo "Error: " . $azubi_query . "<br><br>" . mysqli_error($connection);
    }
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

function getAzubiID ($azubidata)
{
    $gapid=0;
    for ($i = 0; $i<count($azubidata); $i++){
        $id=$i+1;
        if ($id>$azubidata[$i-1]["id"]&&$id<$azubidata[$i]["id"]){
            $gapid=$id;
        }
    }
    if ($gapid != 0){
        return $gapid;
    }
    return $id+1;
}

function getBiggestAzubiID ($azubidata)
{
    $bigid=0;
    for ($i = 0; $i<count($azubidata); $i++){
        $id=$i+1;
        if ($id<$azubidata[$i]["id"]){
            $bigid=$azubidata[$i]["id"];
        }
    }
    if ($bigid != 0){
        return $bigid;
    }
    return $id;
}

function getPageMax($limit,$azubidata)
{
    $allazubicount = count($azubidata);
    return  ceil($allazubicount / $limit);
}

function getGetParameter($key,$default = false)
{
    if (isset($_GET[$key])){
        return $_GET[$key];
    }
    return $default;
}

function getLocationStringAndRedirect($page = false,$dropdown = false,$search = false,$order = false,$orderdirection = false)
{
    $locationstring = "location: ".getConfigParameter("url")."teameditsite.php?";
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

function addSaltGetMD5($password)
{
    return md5(getConfigParameter("salt").$password);
}

function getUrl($sitename = "")
{
    return getConfigParameter("url").$sitename;
}

function getConfigParameter ($key)
{
    if (file_exists("config.php")){
        include "config.php";
    } else {
        exit("Keine config.php gefunden");
    }
    if (isset($configarray[$key])){
        return $configarray[$key];
    }
}