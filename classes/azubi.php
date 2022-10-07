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

    public function __construct($id = null,$name = null,$bday = null,$email = null,$git = null,$employstart = null,$picurl = null,$pass = null,$pre = null,$new = null)
    {
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
        $result = executeMySQLQuery($query);
        $dataArray = mysqli_fetch_all($result,MYSQLI_ASSOC);
        if (!isset($dataArray[0])){
            return;
        }

        $preskills = $this->getSkillArray($id,"pre");
        $newskills = $this->getSkillArray($id,"new");;

        $this->setId($id);
        $this->setName($dataArray[0]["name"]);
        $this->setBday($dataArray[0]["birthday"]);
        $this->setEmail($dataArray[0]["email"]);
        $this->setGithub($dataArray[0]["githubuser"]);
        $this->setEmploystart($dataArray[0]["employmentstart"]);
        $this->setPicurl($dataArray[0]["pictureurl"]);
        $this->setPass($dataArray[0]["password"]);
        $this->setPreskills($preskills);
        $this->setNewskills($newskills);
    }
    public function delete($id = false)
    {
        if ($id === false){
            $id = $this->id;
        }
        $azubi_query = "DELETE FROM azubi WHERE id = $id";
        $skill_query = "DELETE FROM azubi_skills WHERE azubi_id = $id";
        executeMySQLQuery($azubi_query);
        executeMySQLQuery($skill_query);
    }
    public function save()
    {
        $query = "SELECT * FROM azubi WHERE id='$this->id';";
        $result = executeMySQLQuery($query);
        $azubiId = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $idCheck = $azubiId[0]["id"];
        if ($this->id == $idCheck && !empty($idCheck)){
            $this->azubiUpdate();
            $this->skillUpdate($this->preskills,$this->id,"pre");
            $this->skillUpdate($this->newskills,$this->id,"new");
        } else {
            $this->azubiInsert();
            $this->skillInsert($this->preskills,$this->id,"pre");
            $this->skillInsert($this->newskills,$this->id,"new");
        }
    }

    protected function getSkillArray($id,$type)
    {
        $result = executeMySQLQuery("SELECT skill FROM azubi_skills WHERE azubi_id = $id AND type = '$type'");
        $lameArray = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $coolArray = [];
        foreach ($lameArray as $skill){
            $coolArray[]=$skill["skill"];
        }
        return $coolArray;
    }
    protected function skillInsert($skillarray,$azubiid,$skilltype)
    {
        $i = 0;
        if (!is_array($skillarray)){
            $skills[] = $skillarray;
        } else {
            $skills = $skillarray;
        }

        foreach ($skills as $skill){
            if (!empty($skill)){
                $skillquery = "INSERT INTO azubi_skills (azubi_id,type,skill) VALUES (";
                $skillquery .= "'".$azubiid."', '".$skilltype."', '".trim($skill)."')";
                executeMySQLQuery($skillquery);
            }
            $i++;
        }
    }
    protected function skillUpdate($skillarray,$azubiid,$skilltype)
    {
        $oldskills = $this->getSkillArray($azubiid,$skilltype);
        $i = 0;
        foreach ($skillarray as $skill){
            if ($oldskills[$i] !== $skill) {
                $skillquery = "UPDATE azubi_skills SET skill = '$skill' WHERE azubi_id ='$azubiid' AND type = '$skilltype' AND skill = '$oldskills[$i]'";
                executeMySQLQuery($skillquery);
                if ($i >= count($oldskills)){
                    $this->skillInsert($skill,$azubiid,$skilltype);
                }
            }
            $i++;
        }
        if ($i < count($oldskills)){
            for ($o = $i; $o < count($oldskills); $o++){
                $query = "DELETE FROM azubi_skills WHERE azubi_id = '$azubiid' AND skill = '$oldskills[$o]'";
                executeMySQLQuery($query);
                echo $query;
            }
        }
    }
    protected function azubiUpdate()
    {
        $querybegin = "UPDATE azubi ";
        $querymid = "SET ";
        $queryend = "WHERE id = ".$this->id;
        $querymid .= "name="."'".$this->name."',";
        $querymid .= "birthday="."'".$this->birthday."',";
        $querymid .= "email="."'".$this->email."',";
        $querymid .= "githubuser="."'".$this->githubuser."',";
        $querymid .= "employmentstart="."'".$this->employmentstart."',";
        $querymid .= "pictureurl="."'".$this->pictureurl."',";
        $querymid .= "password="."'".$this->password."',";
        $azubiquery=$querybegin.substr($querymid,0,-1).$queryend;
        executeMySQLQuery($azubiquery);
    }
    protected function azubiInsert()
    {
        $querybegin = "INSERT INTO azubi (id,name,birthday,email,githubuser,employmentstart,pictureurl,password";
        $queryend = ") VALUES ( '".$this->id."','".$this->name."','".$this->birthday."','".$this->email."','".$this->githubuser."','".$this->employmentstart."','".$this->pictureurl."','".$this->password."'";
        $query = $querybegin.$queryend.")";
        executeMySQLQuery($query);
    }
}
