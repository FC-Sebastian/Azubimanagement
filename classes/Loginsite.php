<?php

include "Website.php";
class Loginsite extends Website
{
    protected $title = "Login";

    public function validateLoginAndRedirect()
    {
        if ($this->validateAzubiLogin($this->getRequestParameter("loginemail"), $this->getHashedPass())) {
            $_SESSION["logintime"] = time();
            if (isset($_SESSION["origin"])) {
                header("location: " . $this->getUrl("") . str_replace("azubimanagement/", "", $_SESSION["origin"]));
            } else {
                header("location: " . $this->getUrl("teameditsite.php"));
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

            $result = dbconnection::executeMySQLQuery($query);
            $azubilogin = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (!empty($azubilogin)) {
                return true;
            }
        }
    }
}