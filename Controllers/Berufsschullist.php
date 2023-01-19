<?php

class Berufsschullist extends Lists
{
    protected $model = Berufsschule::class;
    protected $blacklist = ["id","street","city","zip"];
}