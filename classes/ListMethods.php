<?php

trait ListMethods
{
    public function getDropdownOptions()
    {
        return $this->dd;
    }

    public function getPage()
    {
        $page = $this->getRequestParameter("page", 1);
        if ($page < 1) {
            $page = 1;
        }
        $this->page = $page;
        return $this->page;
    }

    public function getLimit()
    {
        $this->limit = $this->getRequestParameter("dropdown", 5);
        return $this->limit;
    }

    public function getTableLength()
    {
        if (!empty($this->getRequestParameter("tableLength"))) {
            return intval($this->getRequestParameter("tableLength"));
        }
        if ($this->getLimit() > $this->getNumberOfAzubisLeft($this->getOffset())) {
            return $this->getNumberOfAzubisLeft($this->getOffset());
        }
        return $this->getLimit();
    }

    public function getNumberOfAzubisLeft($offset = false,$search = false)
    {
        $query = "SELECT * FROM azubi";
        if ($search !== false) {
            $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
        }
        if($offset !== false) {
            $query .= " LIMIT " . $offset . "," . $this->getNumberOfAzubisLeft();
        }
        return mysqli_num_rows(DbConnection::executeMysqlQuery($query));
    }

    public function getOffset()
    {
        if (!empty($this->getRequestParameter("offset"))) {
            return $this->getRequestParameter("offset");
        }
        $offset = ($this->getPage() - 1) * $this->getLimit();
        if ($offset < 0) {
            $offset = 0;
        }
        $this->offset = $offset;
        return $this->offset;
    }

    public function getPaginationUrl($direction = 1)
    {
        $string = $this->getUrl("index.php");
        $string .= "?controller=".get_class($this);
        $string .= "&page=" . ($this->getPage() + (1 * $direction));
        if (!empty($this->getRequestParameter("order"))) {
            $string .= "&order=" . $this->getRequestParameter("order");
            $string .= "&orderdir=" . $this->getRequestParameter("orderdir", 0);
        }
        if (isset($this->dd)){
            $string .= "&dropdown=" . $this->getLimit();
        }
        if (!empty($this->getRequestParameter("search"))){
            $string .= "&search=" . $this->getRequestParameter("search");
        }
        if (!empty($this->getRequestParameter("modelName"))){
            $string .= "&modelName=".$this->getRequestParameter("modelName");
        }
        return $string;
    }

    public function getOrderUrl($order)
    {
        $orderdir = -1;
        if (!empty($this->getRequestParameter("orderdir"))) {
            $orderdir = $this->getRequestParameter("orderdir");
        }
        $string = $this->getUrl("index.php");
        $string .= "?controller=".get_class($this);
        $string .= "&order=" . $order . "&orderdir=" . ($orderdir * -1);
        if (isset($this->dd)){
            $string .= "&dropdown=" . $this->getLimit();
        }
        if (!empty($this->getRequestParameter("search"))){
            $string .= "&search=" . $this->getRequestParameter("search");
        }
        if (!empty($this->getRequestParameter("modelName"))){
            $string .= "&modelName=".$this->getRequestParameter("modelName");
        }
        return $string;
    }
}