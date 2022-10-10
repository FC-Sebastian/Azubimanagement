<?php

class Inputsite extends Website
{
    protected $title = "New Azubi";

    public function evaluateRequest()
    {
        if (!empty($_POST)) {
            if ($this->getRequestParameter("pass") === $this->getRequestParameter("confpass") && $this->getRequestParameter("pass") !== false) {
                $password = $this->addSaltGetMD5($this->getRequestParameter("pass"));
            } else {
                $this->redirect("?passmismatch=1&id=" . $this->getRequestParameter("id"));
            }
            if (!empty($this->getRequestParameter("delete")) && $this->getRequestParameter("delete") == "on") {
                $azubi = new azubi($this->getRequestParameter("id"));
                $azubi->delete();
                $this->redirect();
            } elseif (!empty($this->getRequestParameter("id"))) {
                $this->saveAzubi($password);
                $this->redirect();
            } else {
                $this->saveAzubi($password);
                $this->redirect();
            }
            echo "i pooped";
        }
    }

    public function loadAzubiSetTitle($id=false)
    {
        $azubi = new azubi();
        if (!empty($id)) {
            $azubi->load($id);
            $this->title = $azubi->getName();
        }
        return $azubi;
    }

    public function getNameIfNotEmpty($azubi)
    {
        if (!empty($azubi->getName())) {
            return $azubi->getName();
        } else {
            return "New Azubi";
        }
    }

    public function implodeSkills($skills)
    {
        if (!empty($skills)) {
            echo implode(", ", $skills);
        }
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

    protected function redirect($parameters = "")
    {
        header("location: " . $this->getUrl("inputsite.php").$parameters);
        exit();
    }
}