<?php

class Website
{
    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl($sitename = "")
    {
        return conf::getParam("url") . $sitename;
    }

    public function getRequestParameter($key, $default = false)
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return $default;
    }

    public function getPictureUrl($filename)
    {
        if (!empty($filename)) {
            return conf::getParam("url") . "pics/" . $filename;
        }
        return "https://secure.gravatar.com/avatar/cb665e6a65789619c27932fc7b51f8dc?default=mm&size=200&rating=G";
    }

    protected function addSaltGetMD5($password)
    {
        return md5(conf::getParam("salt") . $password);
    }
}