<!DOCTYPE html>
<html>
<head>
    <title>
        <?php
        if (!isset($title)) {
            $title = "Azubi-Team";
        }
        echo $title;
        ?>
    </title>
    <link rel="stylesheet" href="<?php
    echo getUrl("style.css") ?>">
</head>
<body>
    <div class="all">