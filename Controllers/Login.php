<?php

class Login extends BaseController
{
    use AddSaltGetMD5;
    protected $title = "Login";
    protected $startsSession = true;
    protected $view = "loginView";

    public function __construct()
    {
        parent::__construct();
        if ($this->validateAzubiLogin($this->getRequestParameter("loginemail"), $this->getHashedPass())) {
            $_SESSION["logintime"] = time();
            if (isset($_SESSION["origin"])) {
                $this->redirect($this->getUrl("index.php?" . $_SESSION["origin"]));
            } else {
                $this->redirect($this->getUrl("index.php?AzubiList"));
            }
        }
    }

    protected function getHashedPass()
    {
        return $this->addSaltGetMD5($this->getRequestParameter("loginpass"));
    }

    protected function validateAzubiLogin($email, $password)
    {
        if (!empty($email)) {
            $query = "SELECT email, password FROM azubi WHERE email = '$email' AND password = '$password'";

            $result = DbConnection::executeMySQLQuery($query);
            $azubilogin = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (!empty($azubilogin)) {
                return true;
            }
        }
    }
}