<?php
include "functions.php";
$con = dbconnection::getDbConnection();
$allazubi = getAzubiData($con);
include "header.php";
?>
<div class="teamboxes">
    <?php foreach($allazubi as $azu):?>
        <div class="teambox" >
            <a href="<?php echo getUrl("mysite.php")?>?id=<?php echo $azu->getId()?>">
                <img class="teampic" src=<?php echo getPictureUrl($azu->getPicurl())?>
                >
            </a>
            <br>
            <h><?php echo $azu->getName();?></h>
            <div>
                <ul class="contacts">
                    <?php if (!empty($azu->getEmail())):?>
                        <li>
                            <a class="elink" href="mailto:<?php echo $azu->getEmail();?>">
                                <?php echo $azu->getEmail();?>
                            </a>
                        </li>
                    <?php endif;
                    if (!empty($azu->getGithub())):?>
                        <li>
                            <?php echo $azu->getGithub();?>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
            <div>
                <ul class="dates">
                    <li>date of birth</li>
                    <li><b><?php echo $azu->getBday();?></b></li>
                    <li>joined FATCHIP</li>
                    <li><b><?php echo $azu->getEmploystart();?></b></li>
                </ul>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?php
include "footer.php";
mysqli_close($con);
?>
