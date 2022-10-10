<?php

class Listsite extends AzubiDatasite
{
    protected $limit;
    protected $offset;
    protected $page;
    protected $title = "back-end liste";

    public function evaluateRequest()
    {
        if (!empty($_REQUEST)) {
            if (!empty($this->getRequestParameter("delete"))) {
                $this->deleteSingleAzubi();
            }
            if (!empty($this->getRequestParameter("newazubi"))) {
                header("location: " . $this->getUrl("inputsite.php"));
            }
            if ($this->getRequestParameter("deletearray") !== false && $this->getRequestParameter(
                    "deleteselected"
                ) == "ausgewählte Azubis löschen") {
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
    }

    public function getPageMax($limit, $azubidata)
    {
        $allazubicount = count($azubidata);
        return ceil($allazubicount / $limit);
    }

    public function getLimit()
    {
        $this->limit = $this->getRequestParameter("dropdown", 10);
        return $this->limit;
    }

    public function getOffset()
    {
        $offset = ($this->getPage() - 1) * $this->getLimit();
        if ($offset < 0) {
            $offset = 0;
        }
        $this->offset = $offset;
        return $this->offset;
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

    public function getPaginationUrl($direction = 1)
    {
        $string = $this->getUrl("teameditsite.php")."?page=".
            ($this->getPage() + (1 * $direction))."&order=".$this->getRequestParameter("order").
            "&orderdir=".$this->getRequestParameter("orderdir",0)."&dropdown=".
            $this->getLimit()."&search=".$this->getRequestParameter("search");
        return $string;
    }

    public  function getOrderUrl($order)
    {
        $string = $this->getUrl("teameditsite.php")."?order=".$order."&orderdir=".
            ($this->getRequestParameter("orderdir", -1) * -1)."&page=".$this->getPage().
            "&dropdown=".$this->getLimit()."&search=".$this->getRequestParameter("search");
        return $string;
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

}