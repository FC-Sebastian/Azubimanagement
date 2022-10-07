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

    public function __construct($id = "",$name = "",$bday = "",$email = "",$git = "",$employstart = "",$picurl = "",$pass = "",$pre = [],$new = [])
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
        $con = dbconnection::getDbConnection(conf::getParam("dbhost"),conf::getParam("dbuser"),conf::getParam("dbpass"),conf::getParam("db"));
        $query = "SELECT * FROM azubi WHERE id=$id;";
        $result = mysqli_query($con,$query);
        $dataArray = mysqli_fetch_all($result,MYSQLI_ASSOC);

        $preskills = $this->getSkillArray($con,$id,"pre");
        $newskills = $this->getSkillArray($con,$id,"new");;

        $this->setId($dataArray[0]["id"]);
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
        include_once "conf.php";
        include_once "dbconnection.php";
        if ($id === false){
            $id = $this->id;
        }
        $con = dbconnection::getDbConnection(conf::getParam("dbhost"),conf::getParam("dbuser"),conf::getParam("dbpass"),conf::getParam("db"));
        $azubi_query = "DELETE FROM azubi WHERE id = $id";
        $skill_query = "DELETE FROM azubi_skills WHERE azubi_id = $id";
        mysqli_query($con,$azubi_query);
        mysqli_query($con,$skill_query);
    }
    public function save()
    {
        include_once "conf.php";
        include_once "dbconnection.php";
        $con = dbconnection::getDbConnection(conf::getParam("dbhost"),conf::getParam("dbuser"),conf::getParam("dbpass"),conf::getParam("db"));
        $query = "SELECT * FROM azubi;";
        $result = mysqli_query($con,$query);
        $azubidata = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $allId = [];
        foreach ($azubidata as $id){
            $allId[] = $id["id"];
        }
        if (in_array($this->id, $allId)){
            $this->azubiUpdate($azubidata,$con,$allId);
            $this->skillUpdate($this->preskills,$con,$this->id,"pre");
            $this->skillUpdate($this->newskills,$con,$this->id,"new");
        } else {
            $this->azubiInsert($azubidata,$con);
            $this->skillInsert($this->preskills,$con,$this->id,"pre");
            $this->skillInsert($this->newskills,$con,$this->id,"new");
        }
    }

    protected function getAzubiID ($azubidata)
    {
        $gapid=0;
        for ($i = 0; $i<count($azubidata); $i++){
            $id=$i+1;
            if ($i > 0 && $id > $azubidata[$i-1]["id"] && $id < $azubidata[$i]["id"]){
                $gapid=$id;
            }
        }
        if ($gapid != 0){
            return $gapid;
        }
        return $id+1;
    }
    protected function getSkillArray($con,$id,$type)
    {
        $result = mysqli_query($con,"SELECT skill FROM azubi_skills WHERE azubi_id = $id AND type = '$type'");
        $lameArray = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $coolArray = [];
        foreach ($lameArray as $skill){
            $coolArray[]=$skill["skill"];
        }
        return $coolArray;
    }
    protected function skillInsert($skillarray,$connection,$azubiid,$skilltype)
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
                mysqli_query($connection, $skillquery);
            }
            $i++;
        }
    }
    protected function skillUpdate($skillarray,$connection,$azubiid,$skilltype)
    {
        $oldskills = $this->getSkillArray($connection,$azubiid,$skilltype);
        $i = 0;
        foreach ($skillarray as $skill){
            if ($oldskills[$i] !== $skill) {
                $skillquery = "UPDATE azubi_skills SET skill = '$skill' WHERE azubi_id ='$azubiid' AND type = '$skilltype' AND skill = '$oldskills[$i]'";
                mysqli_query($connection, $skillquery);
                if ($i >= count($oldskills)){
                    $this->skillInsert($skill,$connection,$azubiid,$skilltype);

                }

            }
            $i++;
        }
        if ($i < count($oldskills)){
            for ($o = $i; $o < count($oldskills); $o++){
                $query = "DELETE FROM azubi_skills WHERE azubi_id = '$azubiid' AND skill = '$oldskills[$o]'";
                mysqli_query($connection,$query);
                echo $query;
            }
        }
    }
    protected function azubiUpdate($azubidata,$con,$allId)
    {
        $querybegin = "UPDATE azubi ";
        $querymid = "SET ";
        $queryend = "WHERE id = ".$this->id;

        if ($azubidata[array_search($this->id,$allId)]["name"] !== $this->name){
            $querymid .= "name="."'".$this->name."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["birthday"] !== $this->birthday){
            $querymid .= "birthday="."'".$this->birthday."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["email"] !== $this->email){
            $querymid .= "email="."'".$this->email."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["githubuser"] !== $this->githubuser){
            $querymid .= "githubuser="."'".$this->githubuser."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["employmentstart"] !== $this->employmentstart){
            $querymid .= "employmentstart="."'".$this->employmentstart."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["pictureurl"] !== $this->pictureurl){
            $querymid .= "pictureurl="."'".$this->pictureurl."',";
        }
        if ($azubidata[array_search($this->id,$allId)]["password"] !== $this->password){
            $querymid .= "password="."'".$this->password."',";
        }
        $azubiquery=$querybegin.substr($querymid,0,-1).$queryend;
        mysqli_query($con, $azubiquery);
    }
    protected function azubiInsert($azubidata,$con)
    {
        if (empty($this->id)){
            $this->id = $this->getAzubiID($azubidata);
        }
        $querybegin = "INSERT INTO azubi (id,name,birthday,email,githubuser,employmentstart,pictureurl,password";
        $queryend = ") VALUES ( '".$this->id."','".$this->name."','".$this->birthday."','".$this->email."','".$this->githubuser."','".$this->employmentstart."','".$this->pictureurl."','".$this->password."'";
        $query = $querybegin.$queryend.")";
        mysqli_query($con,$query);
    }
}
