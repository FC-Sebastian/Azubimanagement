<?php

class Lists extends BaseController
{
    use ListMethods;

    protected $view = "lists";
    protected $model = "";
    protected $blacklist = [];
    protected $headers = [];
    protected $dd = [1, 5, 10, 20];

    public function getTitle()
    {
        return $this->model . "-Liste";
    }

    public function getRequestData()
    {
        $data = $this->getModelData(
            $this->getRequestParameter("search"),
            $this->getRequestParameter("order"),
            $this->getRequestParameter("orderdir"),
            $this->getlimit(),
            $this->getOffset()
        );
        return $data;
    }

    public function getPageMax()
    {
        $allazubicount = count(
            $this->getModelData(
                $this->getRequestParameter("search"),
                $this->getRequestParameter("order"),
                $this->getRequestParameter("orderdir")
            )
        );
        return ceil($allazubicount / $this->getLimit());
    }

    public function getHeaders()
    {
        if (empty($this->headers)) {
            $this->setHeaders();
        }
        return $this->headers;
    }

    protected function loadModel()
    {
        $modelobject = new $this->model();
        return $modelobject;
    }

    protected function setHeaders()
    {
        $modelobject = $this->loadModel();
        $modelColumns = $modelobject->getColumnNameArray();
        $headers = [];
        foreach ($modelColumns as $column) {
            if (!in_array($column, $this->blacklist)) {
                $headers[] = $column;
            }
        }
        $this->headers = $headers;
    }

    protected function getModelData($search = "", $orderby = "", $orderdir = 0, $limit = false, $offset = 0)
    {
        $modelObject = $this->loadModel();
        $query = "SELECT " . implode(",", $this->getHeaders()) . " FROM " . $modelObject->getTableName();

        if (!empty($search)) {
            $query .= " WHERE ";
            foreach ($this->getHeaders() as $header) {
                $query .= $header . " LIKE '%" . $search . "%' OR ";
            }
            $query = substr($query, 0, -3);
        }

        if (!empty($orderby)) {
            $query .= " ORDER BY " . $orderby;
            if ($orderdir < 0) {
                $query .= " DESC";
            }
        }

        if ($limit !== false) {
            $query .= " LIMIT " . $offset . "," . $limit;
        }

        $result = DbConnection::executeMySQLQuery($query);
        $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $objectarray = [];

        foreach ($resultArray as $array) {
            $modelObject = $this->loadModel();
            foreach ($this->getHeaders() as $key) {
                $setString = "set" . $key;
                $modelObject->$setString($array[$key]);
            }
            $objectarray[] = $modelObject;
        }
        return $objectarray;
    }
}