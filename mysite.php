<?php

include "functions.php";
$website = new Azubisite();
$azubiid = $website->getRequestParameter("id", 1);
$azubidata = new azubi();
$azubidata->load($azubiid);
if (empty($azubidata->getId())): ?>
    <div style="text-align: center; font: 60px 'impact'; color: red; position: relative; top: 40%">
        <b>DIE ANGEGEBENE AZUBI-ID IST NICHT VERGEBEN!</b>
    </div>
    <?php
    exit;
endif;
$website->setTitle($azubidata->getName());
$employmentstart = $azubidata->getEmploystart();
include "header.php"
?>
    <div class="top">
        <a href="https://www.fatchip.de/" target="_blank">
            <img id="logo" src="<?php echo $website->getUrl("pics/fatchip-logo.svg") ?>" alt="Logo fehlt">
        </a>
        <img id="pic" src=<?php echo $website->getPictureUrl($azubidata->getPicurl()) ?>>
        <br><br><br>
        <h1>
            <?php echo $azubidata->getName() ?>
        </h1>
        <h2>Azubi zum Fachinformatiker Anwendungsentwicklung</h2>
            <div>Geb.: <?php echo $azubidata->getBday(); ?>
            </div>
            <div class="email">E-Mail:
                <a class="elink" href= <?php echo "mailto:" . $azubidata->getEmail() ?>><?php echo $azubidata->getEmail() ?>
                </a>
            </div>
            <div>GitHub:
                <?php echo $azubidata->getGithub() ?>
            </div>
        <div id="since"> <?php echo $website->timeSince($employmentstart) ?>
        </div>
    </div>
    <div class="lists">
            <ol>
                <h3>Vorkenntnisse in Programmierung:</h3>
                <?php foreach ($azubidata->getPreskills() as $skill): ?>
                    <li>
                        <?php echo $skill; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
            <ul id="nskills">
                <h4>Bei Fatchip bisher gelernt:</h4>
                <?php foreach ($azubidata->getNewskills() as $skill): ?>
                    <li>
                        <?php echo $skill; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <div id="timeedit">
            <a href="<?php $website->getUrl("inputsite.php") ?>?id=<?php echo $azubidata->getId() ?>">
                edit <?php echo $azubidata->getName() ?>
            </a>
            <div><?php echo date("d.m.y G:i"); ?></div>
        </div>
    </div>
<?php
include "footer.php";
?>