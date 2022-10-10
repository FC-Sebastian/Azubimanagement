<?php

include "Website.php";
class Loginsite extends Website
{
    protected $title = "Login";

    public function getHashedPass()
    {
        return $this->addSaltGetMD5($this->getRequestParameter("loginpass"));
    }
    public function validateAzubiLogin($email, $password)
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