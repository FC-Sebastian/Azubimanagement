<?php

include "Website.php";
class Inputsite extends Website
{
    public function evaluateInput(){
        if ($this->getRequestParameter("pass") === $this->getRequestParameter("confpass") && $this->getRequestParameter("pass") !== false) {
            $password = $this->addSaltGetMD5($this->getRequestParameter("pass"));
        } else {
            header("location: " . $this->getUrl("inputsite.php") . "?passmismatch=1&id=" . $this->getRequestParameter("id"));
            exit();
        }
        if (!empty($this->getRequestParameter("delete")) && $this->getRequestParameter("delete") == "on") {
            $azubi = new azubi($this->getRequestParameter("id"));
            $azubi->delete();
            header("location: " . $this->getUrl("inputsite.php"));
        } elseif (!empty($this->getRequestParameter("id"))) {
            $this->saveAzubi($password);
            header("location: " . $this->getUrl("inputsite.php"));
        } else {
            $this->saveAzubi($password);
            header("location: " . $this->getUrl("inputsite.php"));
        }
        echo "i pooped";
    }

    protected function uploadPictureGetFilename()
    {
        $tmppath = $_FILES["pictureurl"]["tmp_name"];
        $name = $_FILES["pictureurl"]["name"];
        $type = $_FILES["pictureurl"]["type"];
        $locationbegin = __DIR__ . '/pics/';
        $location = $locationbegin . $name;
        $allowed = ["image/jpeg", "image/png", "image/gif", "image/svg+xml", "image/jpg", "	image/webp"];
        if (in_array($type, $allowed)) {
            move_uploaded_file($tmppath, $location);
            return $name;
        }
        return null;
    }
    protected function saveAzubi($password)
    {
        $azubi = new azubi(
            $this->getRequestParameter("id"),
            $this->getRequestParameter("name"),
            $this->getRequestParameter("birthday"),
            $this->getRequestParameter("email"),
            $this->getRequestParameter("githubuser"),
            $this->getRequestParameter("employmentstart"),
            $this->uploadPictureGetFilename(),
            $password,
            explode(",", $this->getRequestParameter("kskills")),
            explode(",", $this->getRequestParameter("nskills"))
        );
        $azubi->save();
    }

}