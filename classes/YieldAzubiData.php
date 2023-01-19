<?php

trait YieldAzubiData
{
    /**
     * azubi generator function
     * yields azubi objects
     * @param $azubiid
     * @param $needle
     * @param $orderby
     * @param $orderdir
     * @param $search
     * @param $limit
     * @param $offset
     * @return Generator
     * @throws Exception
     */
    public function yieldData(
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
        while ($array = mysqli_fetch_assoc($result)) {
            $azubi = new azubi(
                $array["id"],
                $array["name"],
                $array["birthday"],
                $array["email"],
                $array["githubuser"],
                $array["employmentstart"],
                $array["pictureurl"],
                $array["password"]
            );
            yield $azubi;
        }
    }
}