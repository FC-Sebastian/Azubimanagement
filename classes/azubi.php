<?php

class azubi
{
    protected $id;
    protected $name;
    protected $birthday;
    protected $email;
    protected $githubuser;
    protected $employmentstart;
    protected $pictureurl;
    protected $password;
    protected $preskills;
    protected $newskills;

    public function __construct(
        $id = "",
        $name = "",
        $bday = "",
        $email = "",
        $git = "",
        $employstart = "",
        $picurl = "",
        $pass = "",
        $pre = [],
        $new = []
    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setBday($bday);
        $this->setEmail($email);
        $this->setGithub($git);
        $this->setEmploystart($employstart);
        $this->setPicurl($picurl);
        $this->setPass($pass);
        $this->setPreskills($pre);
        $this->setNewskills($new);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($bla)
    {
        $this->name = $bla;
    }

    public function setBday($bla)
    {
        $this->birthday = $bla;
    }

    public function setEmail($bla)
    {
        $this->email = $bla;
    }

    public function setGithub($bla)
    {
        $this->githubuser = $bla;
    }

    public function setEmploystart($bla)
    {
        $this->employmentstart = $bla;
    }

    public function setPicurl($bla)
    {
        $this->pictureurl = $bla;
    }

    public function setPass($bla)
    {
        $this->password = $bla;
    }

    public function setPreskills($bla)
    {
        $this->preskills = $bla;
    }

    public function setNewskills($bla)
    {
        $this->newskills = $bla;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBday()
    {
        return $this->birthday;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getGithub()
    {
        return $this->githubuser;
    }

    public function getEmploystart()
    {
        return $this->employmentstart;
    }

    public function getPicurl()
    {
        return $this->pictureurl;
    }

    public function getPass()
    {
        return $this->password;
    }

    public function getPreskills()
    {
        return $this->preskills;
    }

    public function getNewskills()
    {
        return $this->newskills;
    }

    public function load($id)
    {
        $query = "SELECT * FROM azubi WHERE id=$id;";
        $result = dbconnection::executeMySQLQuery($query);
        if (mysqli_num_rows($result) == 0) {
            return;
        }
        $dataArray = mysqli_fetch_assoc($result);
        $this->setId($id);
        $this->setName($dataArray["name"]);
        $this->setBday($dataArray["birthday"]);
        $this->setEmail($dataArray["email"]);
        $this->setGithub($dataArray["githubuser"]);
        $this->setEmploystart($dataArray["employmentstart"]);
        $this->setPicurl($dataArray["pictureurl"]);
        $this->setPass($dataArray["password"]);
        $this->setPreskills($this->getSkillArray("pre"));
        $this->setNewskills($this->getSkillArray("new"));
    }

    public function delete($id = false)
    {
        if ($id === false) {
            $id = $this->getId();
        }
        $azubi_query = "DELETE FROM azubi WHERE id = $id";
        $skill_query = "DELETE FROM azubi_skills WHERE azubi_id = $id";
        dbconnection::executeMySQLQuery($azubi_query);
        dbconnection::executeMySQLQuery($skill_query);
    }

    public function save()
    {
        $query = "SELECT * FROM azubi WHERE id='" . $this->getId() . "';";
        $result = dbconnection::executeMySQLQuery($query);
        $azubiId = mysqli_fetch_assoc($result);
        if ($this->getId() == $azubiId["id"] && !empty($azubiId["id"])) {
            $this->azubiUpdate();
            $this->skillUpdate($this->getPreskills(), "pre");
            $this->skillUpdate($this->getNewskills(), "new");
        } else {
            $this->azubiInsert();
            $this->skillInsert($this->getPreskills(), "pre");
            $this->skillInsert($this->getNewskills(), "new");
        }
    }

    protected function getSkillArray($type)
    {
        $result = dbconnection::executeMySQLQuery(
            "SELECT skill FROM azubi_skills WHERE azubi_id = " . $this->getId() . " AND type = '$type'"
        );
        $lameArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $coolArray = [];
        foreach ($lameArray as $skill) {
            $coolArray[] = $skill["skill"];
        }
        return $coolArray;
    }

    protected function skillInsert($skillarray, $skilltype)
    {
        $i = 0;
        if (!is_array($skillarray)) {
            $skills[] = $skillarray;
        } else {
            $skills = $skillarray;
        }

        foreach ($skills as $skill) {
            if (!empty($skill)) {
                $skillquery = "INSERT INTO azubi_skills (azubi_id,type,skill) VALUES (";
                $skillquery .= "'" . $this->getId() . "', '" . $skilltype . "', '" . trim($skill) . "')";
                dbconnection::executeMySQLQuery($skillquery);
            }
            $i++;
        }
    }

    protected function skillUpdate($skillarray, $skilltype)
    {
        $oldskills = $this->getSkillArray($skilltype);
        $i = 0;
        foreach ($skillarray as $skill) {
            if ($oldskills[$i] !== $skill) {
                $skillquery = "UPDATE azubi_skills SET skill = '$skill' WHERE azubi_id ='" . $this->getId(
                    ) . "' AND type = '$skilltype' AND skill = '$oldskills[$i]'";
                dbconnection::executeMySQLQuery($skillquery);
                if ($i >= count($oldskills)) {
                    $this->skillInsert($skill, $skilltype);
                }
            }
            $i++;
        }
        if ($i < count($oldskills)) {
            for ($o = $i; $o < count($oldskills); $o++) {
                $query = "DELETE FROM azubi_skills WHERE azubi_id = '" . $this->getId(
                    ) . "' AND skill = '$oldskills[$o]'";
                dbconnection::executeMySQLQuery($query);
                echo $query;
            }
        }
    }

    protected function azubiUpdate()
    {
        $querybegin = "UPDATE azubi ";
        $querymid = "SET ";
        $queryend = "WHERE id = " . $this->getId();
        $querymid .= "name=" . "'" . $this->getName() . "',";
        $querymid .= "birthday=" . "'" . $this->getBday() . "',";
        $querymid .= "email=" . "'" . $this->getEmail() . "',";
        $querymid .= "githubuser=" . "'" . $this->getGithub() . "',";
        $querymid .= "employmentstart=" . "'" . $this->getEmploystart() . "',";
        $querymid .= "pictureurl=" . "'" . $this->getPicurl() . "',";
        $querymid .= "password=" . "'" . $this->getPass() . "',";
        $azubiquery = $querybegin . substr($querymid, 0, -1) . $queryend;
        dbconnection::executeMySQLQuery($azubiquery);
    }

    protected function azubiInsert()
    {
        $querybegin = "INSERT INTO azubi (id,name,birthday,email,githubuser,employmentstart,pictureurl,password";
        $queryend = ") VALUES ( '" . $this->getId() . "','" . $this->getName() . "','" . $this->getBday(
            ) . "','" . $this->getEmail() . "','" . $this->getGithub() . "','" . $this->getEmploystart(
            ) . "','" . $this->getPicurl() . "','" . $this->getPass() . "'";
        $query = $querybegin . $queryend . ")";
        dbconnection::executeMySQLQuery($query);
    }
}
