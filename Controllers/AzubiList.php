<?php


class AzubiList extends SecureController
{
    use AzubiData;
    use YieldAzubiData;
    use ListMethods;

    protected $limit;
    protected $offset;
    protected $page;
    protected $title = "back-end liste";
    protected $dd = [1, 5, 10, 20];
    protected $view = "azubiList";
    protected $onloadFuntction;

    public function __construct()
    {
        parent::__construct();
        $this->onloadFuntction = "onSiteLoad(". $this->getTableLength() . "," . $this->getOffset() . ")";

    }

    public function getPageMax()
    {
        $allazubicount = $this->getNumberOfAzubisLeft(false,$this->getRequestParameter("search"));
        return ceil($allazubicount / $this->getLimit());
    }

    public function getRequestData()
    {
        $azubidata = $this->getAzubiData(
            false,
            "*",
            $this->getRequestParameter("order"),
            $this->getRequestParameter("orderdir"),
            $this->getRequestParameter("search"),
            $this->getlimit(),
            $this->getOffset()
        );
        return $azubidata;
    }

    public function deleteSingleAzubi()
    {
        $azubi = new azubi();
        $azubi->delete($this->getRequestParameter("delete"));
    }

    public function deleteMultiplAzubis()
    {
        if(empty($this->getRequestParameter("deletearray"))) {
            throw new Exception("keine Azubis ausgewählt");
        }
        foreach ($this->getRequestParameter("deletearray") as $id) {
            $azubi = new azubi();
            $azubi->delete($id);
        }
    }

    public function getBgOpacity($colorIterator)
    {
        if ($colorIterator % 2 === 0){
            return "25";
        }
        return "50";
    }
}