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
        $con = dbconnection::getDbConnection();
        $query = "SELECT * FROM azubi WHERE id=$id;";
        $result = executeMySQLQuery($con,$query);
        $dataArray = mysqli_fetch_all($result,MYSQLI_ASSOC);

        $preskills = $this->getSkillArray($con,$id,"pre");
        $newskills = $this->getSkillArray($con,$id,"new");;

        $this->setId($id);
        $this->setName(getValueIfIsset($dataArray,"name"));
        $this->setBday(getValueIfIsset($dataArray,"birthday"));
        $this->setEmail(getValueIfIsset($dataArray,"email"));
        $this->setGithub(getValueIfIsset($dataArray,"githubuser"));
        $this->setEmploystart(getValueIfIsset($dataArray,"employmentstart"));
        $this->setPicurl(getValueIfIsset($dataArray,"pictureurl"));
        $this->setPass(getValueIfIsset($dataArray,"password"));
        $this->setPreskills($preskills);
        $this->setNewskills($newskills);
    }
    public function delete($id = false)
    {
        if ($id === false){
            $id = $this->id;
        }
        $con = dbconnection::getDbConnection();
        $azubi_query = "DELETE FROM azubi WHERE id = $id";
        $skill_query = "DELETE FROM azubi_skills WHERE azubi_id = $id";
        executeMySQLQuery($con,$azubi_query);
        executeMySQLQuery($con,$skill_query);
    }
    public function save()
    {
        $con = dbconnection::getDbConnection();
        $query = "SELECT * FROM azubi WHERE id='$this->id';";
        $result = executeMySQLQuery($con,$query);
        $azubiId = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $idCheck = $azubiId[0]["id"];
        if ($this->id == $idCheck && !empty($idCheck)){
            $this->azubiUpdate($con);
            $this->skillUpdate($this->preskills,$con,$this->id,"pre");
            $this->skillUpdate($this->newskills,$con,$this->id,"new");
        } else {
            $this->azubiInsert($con);
            $this->skillInsert($this->preskills,$con,$this->id,"pre");
            $this->skillInsert($this->newskills,$con,$this->id,"new");
        }
    }

    protected function getSkillArray($con,$id,$type)
    {
        $result = executeMySQLQuery($con,"SELECT skill FROM azubi_skills WHERE azubi_id = $id AND type = '$type'");
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
                executeMySQLQuery($connection, $skillquery);
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
                executeMySQLQuery($connection, $skillquery);
                if ($i >= count($oldskills)){
                    $this->skillInsert($skill,$connection,$azubiid,$skilltype);
                }
            }
            $i++;
        }
        if ($i < count($oldskills)){
            for ($o = $i; $o < count($oldskills); $o++){
                $query = "DELETE FROM azubi_skills WHERE azubi_id = '$azubiid' AND skill = '$oldskills[$o]'";
                executeMySQLQuery($connection,$query);
                echo $query;
            }
        }
    }
    protected function azubiUpdate($con)
    {
        $querybegin = "UPDATE azubi ";
        $querymid = "SET ";
        $queryend = "WHERE id = ".$this->id;

        if (isset($this->name)){
            $querymid .= "name="."'".$this->name."',";
        }
        if (isset($this->birthday)){
            $querymid .= "birthday="."'".$this->birthday."',";
        }
        if (isset($this->email)){
            $querymid .= "email="."'".$this->email."',";
        }
        if (isset($this->githubuser)){
            $querymid .= "githubuser="."'".$this->githubuser."',";
        }
        if (isset($this->employmentstart)){
            $querymid .= "employmentstart="."'".$this->employmentstart."',";
        }
        if (isset($this->pictureurl)){
            $querymid .= "pictureurl="."'".$this->pictureurl."',";
        }
        if (isset($this->password)){
            $querymid .= "password="."'".$this->password."',";
        }
        $azubiquery=$querybegin.substr($querymid,0,-1).$queryend;
        executeMySQLQuery($con, $azubiquery);
    }
    protected function azubiInsert($con)
    {
        $querybegin = "INSERT INTO azubi (id,name,birthday,email,githubuser,employmentstart,pictureurl,password";
        $queryend = ") VALUES ( '".$this->id."','".$this->name."','".$this->birthday."','".$this->email."','".$this->githubuser."','".$this->employmentstart."','".$this->pictureurl."','".$this->password."'";
        $query = $querybegin.$queryend.")";
        executeMySQLQuery($con,$query);
    }
}
