<?php

class SecureController extends BaseController
{
    protected $isActive = false;
    protected $startsSession = true;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION["logintime"]) && $this->isActive === true) {
            $_SESSION["origin"] = $_SERVER["QUERY_STRING"];
            $this->redirect($this->getUrl("index.php?controller=Login"));
        }
        if (isset($_SESSION["logintime"])) {
            if ((time() - $_SESSION["logintime"]) >= 600) {
                session_unset();
                session_destroy();
            }
        }
    }
}