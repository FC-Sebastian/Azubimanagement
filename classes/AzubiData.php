<?php

trait AzubiData
{
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
        $result = DbConnection::executeMySQLQuery($query);
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
}