<?php

class AjaxDeleteSkill extends AjaxSqlQueryController
{

    protected function executeSqlQuery()
    {
        $type = $this->getRequestParameter("type");
        $skill = $this->getRequestParameter("skill");
        $azubiId = $this->getRequestParameter("id");

        $query = "DELETE FROM azubi_skills WHERE azubi_id='$azubiId' AND skill='$skill' AND type='$type'";
        DbConnection::executeMysqlQuery($query);

    }
}