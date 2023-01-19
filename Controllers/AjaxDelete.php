<?php

class AjaxDelete extends AjaxSqlQueryController
{
    protected function executeSqlQuery()
    {
        if (!empty($this->getRequestParameter("id"))) {
            $azubi = new Azubi();
            $azubi->delete($this->getRequestParameter("id"));
        }
    }
}