<?php

class BerufsschulklasseList extends Lists
{
    protected $model = Berufsschulklasse::class;
    protected $blacklist = ["id"];
}