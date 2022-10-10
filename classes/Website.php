<?php

class Website
{

    public function __construct()
    {
        include "conf.php";
        include "dbconnection.php";
        include "azubi.php";
    }
    public function getAzubiData(
        $azubiid = false,
        $needle = "*",
        $orderby = false,
        $orderdir = 0,
        $search = false,
        $limit = false,
        $offset = 0
    ) {
        $query = "SELECT $needle FROM azubi";
        if (!empty($search)) {
            $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
        }
        if ($azubiid !== false && !empty($search)) {
            $query .= " AND id = '$azubiid'";
        } elseif ($azubiid !== false) {
            $query .= " WHERE id='$azubiid'";
        }
        if (!empty($orderby)) {
            $query .= " ORDER BY " . $orderby;
        }
        if ($orderdir < 0) {
            $query .= " DESC";
        }
        if ($limit !== false) {
            $query .= " LIMIT " . $offset . "," . $limit;
        }
        $query .= ";";
        $result = dbconnection::executeMySQLQuery($query);
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $objectarray = [];
        foreach ($array as $azu) {
            $azubi = new azubi(
                $azu["id"],
                $azu["name"],
                $azu["birthday"],
                $azu["email"],
                $azu["githubuser"],
                $azu["employmentstart"],
                $azu["pictureurl"],
                $azu["password"]
            );
            $objectarray[] = $azubi;
        }
        return $objectarray;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getUrl($sitename = "")
    {
        return conf::getParam("url") . $sitename;
    }
    public function getRequestParameter($key, $default = false)
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return $default;
    }
    public function getPictureUrl($filename)
    {
        if (!empty($filename)) {
            return conf::getParam("url") . "pics/" . $filename;
        }
        return "https://secure.gravatar.com/avatar/cb665e6a65789619c27932fc7b51f8dc?default=mm&size=200&rating=G";
    }
    protected function addSaltGetMD5($password)
    {
        return md5(conf::getParam("salt") . $password);
    }
}