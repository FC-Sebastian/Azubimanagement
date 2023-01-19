<?php

trait AddSaltGetMD5
{
    protected function addSaltGetMD5($password)
    {
        return md5(conf::getParam("salt") . $password);
    }
}