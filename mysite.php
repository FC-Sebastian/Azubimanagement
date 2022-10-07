<?php

include "functions.php";
include_once "classes/dbconnection.php";
$con = dbconnection::getDbConnection();
$azubiid = getRequestParameter("id", 1);
$azubidata = new azubi();
$azubidata->load($azubiid);
?>
<?php
if (empty($azubidata)): ?>
    <div style="text-align: center; font: 60px 'impact'; color: red; position: relative; top: 40%">
        <b>DIE ANGEGEBENE AZUBI-ID IST NICHT VERGEBEN!</b>
    </div>
    <?php
    exit;
endif;
$title = $azubidata->getName();
$employmentstart = $azubidata->getEmploystart();
include "header.php"
?>
    <div class="top">
        <a href="https://www.fatchip.de/" target="_blank">
            <img id="logo" src="<?php
            echo getUrl("pics/fatchip-logo.svg") ?>" alt="Logo fehlt">
        </a>
        <img id="pic" src=<?php
        echo getPictureUrl($azubidata->getPicurl()) ?>>
        <br><br><br>
        <h1>
            <?php
            echo $azubidata->getName() ?>
        </h1>
        <h2>Azubi zum Fachinformatiker Anwendungsentwicklung</h2>
        <?php
        if (!empty($azubidata->getBday())): ?>
            <div>Geb.: <?php
                echo $azubidata->getBday(); ?></div>
        <?php
        endif;
        if (!empty($azubidata->getEmail())): ?>
            <div class="email">E-Mail:
                <a class="elink" href= <?php
                echo "mailto:" . $azubidata->getEmail() ?>><?php
                    echo $azubidata->getEmail() ?></a>
            </div>
        <?php
        endif ?>
        <?php
        if (!empty($azubidata->getGithub())): ?>
            <div>GitHub:
                <?php
                echo $azubidata->getGithub() ?>
            </div>
        <?php
        endif ?>
        <div id="since"> <?php
            echo timeSince($employmentstart) ?> </div>
    </div>
    <div class="lists">
        <?php
        if (!empty($azubidata->getPreskills())): ?>
            <ol>
                <h3>Vorkenntnisse in Programmierung:</h3>
                <?php
                foreach ($azubidata->getPreskills() as $skill): ?>
                    <li>
                        <?php
                        echo $skill; ?>
                    </li>
                <?php
                endforeach; ?>
            </ol>
        <?php
        endif; ?>
        <?php
        if (!empty($azubidata->getNewskills())): ?>
            <ul id="nskills">
                <h4>Bei Fatchip bisher gelernt:</h4>
                <?php
                foreach ($azubidata->getNewskills() as $skill): ?>
                    <li>
                        <?php
                        echo $skill; ?>
                    </li>
                <?php
                endforeach; ?>
            </ul>
        <?php
        endif; ?>
        <div id="timeedit">
            <a href="<?php
            getUrl("inputsite.php") ?>?id=<?php
            echo $azubidata->getId() ?>">edit <?php
                echo $azubidata->getName() ?></a>
            <div><?php
                echo date("d.m.y G:i"); ?></div>
        </div>
    </div>
<?php
include "footer.php";
mysqli_close($con);
?>