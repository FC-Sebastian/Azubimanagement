<?php

class SearchbarController extends JSONController
{
    /**
     * gets array of names and returns array of matches
     * @return array
     * @throws Exception
     */
    protected function getJsonData():array
    {
        $nameArray = $this->getAzubiNames();
        return $this->getFormattedMatches($nameArray);
    }

    /**
     * returns Array containing formatted matches from request parameter "search" and the given array of names
     * @param $nameArray
     * @return array
     */
    protected function getFormattedMatches($nameArray):array
    {
        $search = $this->getRequestParameter("search");
        $matches = [];
        foreach ($nameArray as $name) {
            if (is_int(strpos(strtolower($name), strtolower($search)))) {
                if ($this->addHtmlTags2Search($search,$name) !== false) {
                    $matches[] = $this->addHtmlTags2Search($search,$name);
                }
            }
        }
        return $matches;
    }

    /**
     * returns name string with <u> and <b> tags around search
     * returns false if search is not at the beginning of a word in name
     * @param $search
     * @param $name
     * @return string
     */
    public function addHtmlTags2Search($search, $name)
    {
        if (is_int(strpos($name, " ")) && !is_int(strpos($search," "))) {
            $names = explode(" ", $name);
            $implodeArray = [];
            foreach ($names as $n) {
                if (strpos(strtolower($n), strtolower($search)) === 0) {
                    $n1 = substr($n,0,strlen($search));
                    $n2 = substr($n,strlen($search));
                    $n1 = "<b><u>" . $n1 . "</u></b>";
                    $n = $n1 . $n2;
                }
                $implodeArray[] = $n;
            }
            if ($implodeArray === $names) {
                return false;
            }
            return ucwords(implode(" ", $implodeArray));
        } else {
            if (strpos(strtolower($name), strtolower($search)) === 0) {
                $n1 = substr($name,0,strlen($search));
                $n2 = substr($name,strlen($search));
                $n1 = "<b><u>" . $n1 . "</u></b>";
                return $n1 . $n2;
            }
            return false;
        }
    }

    /**
     * selects all names from azubi and returns them as array
     * @return array
     * @throws Exception
     */
    protected function getAzubiNames():array
    {
        $query = "SELECT name FROM azubi";
        $result = DbConnection::executeMySQLQuery($query);
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $returnArray = [];
        foreach ($array as $azu) {
            $returnArray[] = $azu["name"];
        }
        return $returnArray;
    }
}