<?php
include "functions.php";
$con = getDatabaseConnection();
$azubiid = getRequestParameter("id",1);
$azubipreskills = getSkillsByType($con, $azubiid, "pre");
$azubinewskills = getSkillsByType($con, $azubiid, "new");
$azubidatatemp = getAzubiData($con,$azubiid);
?>
<?php if (empty($azubidatatemp)):?>
    <div style="text-align: center; font: 60px 'impact'; color: red; position: relative; top: 40%">
        <b>DIE ANGEGEBENE AZUBI-ID IST NICHT VERGEBEN!</b>
    </div>
<?php
exit;
endif;
$azubidata = $azubidatatemp[0];
$title = $azubidata["name"];
$employmentstart = $azubidata["employmentstart"];
include "header.php"
?>
<div class="top">
    <a href="https://www.fatchip.de/" target="_blank">
        <img id="logo" src="<?php echo getUrl("pics/fatchip-logo.svg")?>" alt="Logo fehlt">
    </a>
        <img id="pic" src=<?php echo getPictureUrl($azubidata["pictureurl"]) ?>>
        <br><br><br>
        <h1>
            <?php echo $azubidata["name"]?>
        </h1>
        <h2>Azubi zum Fachinformatiker Anwendungsentwicklung</h2>
        <?php if (!empty($azubidata["birthday"])):?>
            <div>Geb.: <?php echo $azubidata["birthday"];?></div>
        <?php endif; if (!empty($azubidata["email"])):?>
            <div  class="email">E-Mail:
                <a class="elink" href= <?php echo "mailto:".$azubidata["email"]?>><?php echo $azubidata["email"]?></a>
            </div>
        <?php endif?>
        <?php if (!empty($azubidata["githubuser"])):?>
            <div >GitHub:
                    <?php echo $azubidata["githubuser"]?>
            </div>
        <?php endif?>
        <div id="since" > <?php echo timeSince($employmentstart)?> </div>
    </div>
    <div class="lists">
        <?php if (!empty($azubipreskills)):?>
            <ol>
                <h3>Vorkenntnisse in Programmierung:</h3>
                <?php foreach ($azubipreskills as $skill):?>
                    <li>
                        <?php echo $skill["skill"];?>
                    </li>
                <?php endforeach;?>
            </ol>
        <?php endif; ?>
        <?php if (!empty($azubinewskills)):?>
            <ul id="nskills">
                <h4>Bei Fatchip bisher gelernt:</h4>
                <?php foreach ($azubinewskills as $skill):?>
                    <li>
                        <?php echo $skill["skill"];?>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif; ?>
        <div id="timeedit">
            <a href="<?php getUrl("inputsite.php")?>?id=<?php echo getValueIfIsset($azubidata,"id")?>">edit <?php echo getValueIfIsset($azubidata,"name")?></a>
            <div><?php echo date("d.m.y G:i");?></div>
        </div>
    </div>
<?php
include  "footer.php";
mysqli_close($con);
?>