<?php

class DynamicAzubiList extends Lists
{
    protected $model = Azubi::class;
    protected $blacklist = ["id","birthday","pictureurl","password"];
}