<?php

include "Website.php";
class Listsite extends Website
{
    protected $limit;
    protected $offset;
    protected $page;
    protected $pagei = 1;
    protected $ddoptions = [1, 5, 10, 20];
    protected $title = "back-end liste";

    public function __construct()
    {
        include "conf.php";
        include "dbconnection.php";
        include "azubi.php";
        $this->setLimit();
        $this->setPage();
        $this->setOffset($this->getPage(),$this->getLimit());
    }

    public function evaluateRequest()
    {
        if (!empty($this->getRequestParameter("delete"))) {
            $this->deleteSingleAzubi();
        }
        if (!empty($this->getRequestParameter("newazubi"))) {
            header("location: " . $this->getUrl("inputsite.php"));
        }
        if ($this->getRequestParameter("deletearray") !== false  && $this->getRequestParameter("deleteselected") == "ausgewählte Azubis löschen") {
            $this->deleteMultiplAzubis();
        }
        if (!empty($this->getRequestParameter("submitsearch"))) {
            $this->getLocationStringAndRedirect(
                1,
                $this->getRequestParameter("dropdown"),
                $this->getRequestParameter("search"),
                $this->getRequestParameter("order"),
                $this->getRequestParameter("orderdir")
            );
        }
        if (!empty($this->getRequestParameter("ddsubmit"))) {
            $this->getLocationStringAndRedirect(
                1,
                $this->getRequestParameter("dropdown"),
                $this->getRequestParameter("lastsearch"),
                $this->getRequestParameter("order"),
                $this->getRequestParameter("orderdir")
            );
        }
        if (!empty($this->getRequestParameter("pageselect"))) {
            $this->getLocationStringAndRedirect(
                $this->getRequestParameter("pageselect"),
                $this->getRequestParameter("dropdown"),
                $this->getRequestParameter("lastsearch"),
                $this->getRequestParameter("order"),
                $this->getRequestParameter("orderdir")
            );
        }
    }
    public function getPageMax($limit, $azubidata)
    {
        $allazubicount = count($azubidata);
        return ceil($allazubicount / $limit);
    }
    public function getLimit()
    {
        return $this->limit;
    }
    public function getOffset()
    {
        return $this->offset;
    }
    public function getPage()
    {
        return $this->page;
    }
    public function getDd()
    {
        return $this->ddoptions;
    }
    public function getPageI()
    {
        return $this->pagei;
    }

    protected function getLocationStringAndRedirect(
        $page = false,
        $dropdown = false,
        $search = false,
        $order = false,
        $orderdirection = false
    ) {
        $locationstring = "location: " . conf::getParam("url") . "teameditsite.php?";
        if (false !== $page) {
            $locationstring .= "page=" . $page;
        }
        if (false !== $dropdown) {
            $locationstring .= "&dropdown=" . $dropdown;
        }
        if (false !== $search) {
            $locationstring .= "&search=" . $search;
        }
        if (false !== $order) {
            $locationstring .= "&order=" . $order . "&orderdir=" . $orderdirection;
        }
        header($locationstring);
    }
    protected function deleteSingleAzubi()
    {
        $azubi = new azubi();
        $azubi->delete($this->getRequestParameter("delete"));
        $this->getLocationStringAndRedirect(
            1,
            $this->getRequestParameter("dropdown"),
            $this->getRequestParameter("lastsearch"),
            $this->getRequestParameter("order"),
            $this->getRequestParameter("orderdir")
        );
    }
    protected function deleteMultiplAzubis()
    {
        foreach ($this->getRequestParameter("deletearray") as $id) {
            $azubi = new azubi();
            $azubi->delete($id);
        }
        $this->getLocationStringAndRedirect(
            $this->getRequestParameter("page"),
            $this->getRequestParameter("dropdown"),
            $this->getRequestParameter("lastsearch"),
            $this->getRequestParameter("order"),
            $this->getRequestParameter("orderdir")
        );
    }
    protected function setLimit()
    {
        $this->limit = $this->getRequestParameter("dropdown", 10);
    }
    protected function setOffset($page,$limit)
    {
        $offset = ($page - 1) * $limit;
        if ($offset < 0) {
            $offset = 0;
        }
        $this->offset = $offset;
    }
    protected function setPage()
    {
        $page = $this->getRequestParameter("page", 1);
        if ($page < 1) {
            $page = 1;
        }
        $this->page = $page;
    }
}