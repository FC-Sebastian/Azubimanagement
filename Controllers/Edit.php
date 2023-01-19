<?php

class Edit extends SecureController
{
    use AddSaltGetMD5;
    protected $view = "editView";
    protected $title = "New Azubi";

    public function delete()
    {
        $azubi = new azubi($this->getRequestParameter("id"));
        $azubi->delete();
    }

    public function updateInsert()
    {
        $this->validateInput();
        $this->saveAzubi();
    }

    public function validateInput()
    {
        return;
        if (empty($this->getRequestParameter("name"))){
            throw new Exception("name not entered");
        }
        if (!filter_var($this->getRequestParameter("email"), FILTER_VALIDATE_EMAIL)){
            throw new Exception("email not valid");
        }
        $this->validateDate("birthday");
        $this->validateGitHubUrl();
        $this->validateDate("employmentstart");
        if ($this->getRequestParameter("pass") !== $this->getRequestParameter("confpass") || $this->getRequestParameter("pass") === "") {
            throw new Exception("password not entered correctly");
        }
    }

    public function validateGitHubUrl()
    {
        $url = "https://github.com/" . $this->getRequestParameter("githubuser");
        $headers = get_headers($url);
        if (!$headers || strpos($headers[0], '404')) {
            throw new Exception("github doesnt exist");
        }
    }

    public function validateDate($requestKey)
    {
        $date = $this->getRequestParameter($requestKey);

        $date = explode("-",$date);
        if (!isset($date[1]) || !checkdate($date[1],$date[2],$date[0])){
            throw new Exception( $requestKey . " not entered");
        }
    }

    public function loadAzubi()
    {
        $azubi = new azubi();
        if (!empty($this->getRequestParameter("id"))) {
            $azubi->load($this->getRequestParameter("id"));
        }
        return $azubi;
    }

    public function getTitle()
    {
        $azubi = $this->loadAzubi();
        $title = $azubi->getName();
        if (empty($title)){
            $title = "New Azubi";
        }
        return $title;
    }

    protected function uploadPictureGetFilename()
    {
        $tmppath = $_FILES["pictureurl"]["tmp_name"];
        $name = $_FILES["pictureurl"]["name"];
        $type = $_FILES["pictureurl"]["type"];
        $locationbegin = __DIR__ . '/../pics/';
        $location = $locationbegin . $name;
        $allowed = ["image/jpeg", "image/png", "image/gif", "image/svg+xml", "image/jpg", "	image/webp"];
        if (in_array($type, $allowed)) {
            move_uploaded_file($tmppath, $location);
            return $name;
        }
        return null;
    }

    protected function saveAzubi()
    {
        $azubi = new azubi(
            $this->getRequestParameter("id"),
            $this->getRequestParameter("name"),
            $this->getRequestParameter("birthday"),
            $this->getRequestParameter("email"),
            $this->getRequestParameter("githubuser"),
            $this->getRequestParameter("employmentstart"),
            $this->uploadPictureGetFilename(),
            $this->addSaltGetMD5($this->getRequestParameter("pass")),
            $this->getRequestParameter("kskills"),
            $this->getRequestParameter("nskills")
        );
        $azubi->save();
    }
}