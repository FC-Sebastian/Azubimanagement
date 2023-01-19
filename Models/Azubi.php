<?php


class Azubi extends BaseModel
{
    protected $tablename = "azubi";
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
        $this->setBirthday($bday);
        $this->setEmail($email);
        $this->setGithubuser($git);
        $this->setEmploymentstart($employstart);
        $this->setPictureurl($picurl);
        $this->setPassword($pass);
        $this->setPreskills($pre);
        $this->setNewskills($new);
    }

    public function setPreskills($bla)
    {
        $this->preskills = $bla;
    }

    public function setNewskills($bla)
    {
        $this->newskills = $bla;
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
        parent::load($id);
        if (!empty($this->getId())){
            $this->setPreskills($this->getSkillArray("pre"));
            $this->setNewskills($this->getSkillArray("new"));
        }
    }

    public function delete($id = false)
    {
        parent::delete($id);
        $skill_query = "DELETE FROM azubi_skills WHERE azubi_id = $id";
        DbConnection::executeMySQLQuery($skill_query);
    }

    public function save()
    {
        $query = "SELECT id FROM azubi WHERE id='" . $this->getId() . "';";
        $result = DbConnection::executeMySQLQuery($query);
        $azubiId = mysqli_fetch_assoc($result);
        if (isset($azubiId["id"]) && $this->getId() == $azubiId["id"]) {
            $this->update();
            $this->skillUpdate($this->getPreskills(), "pre");
            $this->skillUpdate($this->getNewskills(), "new");
        } else {
            $this->insert();
            $this->skillInsert($this->getPreskills(), "pre");
            $this->skillInsert($this->getNewskills(), "new");
        }
    }

    protected function getSkillArray($type)
    {
        $result = DbConnection::executeMySQLQuery(
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
                $skillquery .= "'" . $this->getId() . "', '" . $skilltype . "', '" . $skill . "')";
                DbConnection::executeMySQLQuery($skillquery);
            }
            $i++;
        }
    }

    protected function skillUpdate($skillarray, $skilltype)
    {
        $oldskills = $this->getSkillArray($skilltype);
        $i = 0;
        foreach ($skillarray as $skill) {
            if (isset($oldskills[$i]) && $oldskills[$i] != $skill) {
                $skillquery = "UPDATE azubi_skills SET skill = '$skill' WHERE azubi_id ='" . $this->getId(
                    ) . "' AND type = '$skilltype' AND skill = '$oldskills[$i]'";
                DbConnection::executeMySQLQuery($skillquery);
            }
            if ($i >= count($oldskills)) {
                $this->skillInsert($skill, $skilltype);
            }
            $i++;
        }
        if ($i < count($oldskills)) {
            for ($o = $i; $o < count($oldskills); $o++) {
                $query = "DELETE FROM azubi_skills WHERE azubi_id = '" . $this->getId(
                    ) . "' AND skill = '$oldskills[$o]'";
                DbConnection::executeMySQLQuery($query);
            }
        }
    }
}