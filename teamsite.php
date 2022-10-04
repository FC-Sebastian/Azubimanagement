<?php
include "functions.php";
$con = getDatabaseConnection();
$allazubi = getAzubiData($con);
include "header.php";
?>
<div class="teamboxes">
    <?php foreach($allazubi as $azu):?>
        <div class="teambox" >
            <a href="http://localhost/mysite.php?id=<?php echo $azu["id"]?>">
                <img class="teampic" src=<?php echo getPictureUrl($azu["pictureurl"])?>
                >
            </a>
            <br>
            <h><?php echo $azu["name"];?></h>
            <div>
                <ul class="contacts">
                    <?php if (!empty($azu["email"])):?>
                        <li>
                            <a class="elink" href="mailto:<?php echo $azu["email"];?>">
                                <?php echo $azu["email"];?>
                            </a>
                        </li>
                    <?php endif;
                    if (!empty($azu["githubuser"])):?>
                        <li>
                            <a href="<?php echo $azu["githubuserlink"];?>" target="_blank">
                                <?php echo $azu["githubuser"];?>
                            </a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
            <div>
                <ul class="dates">
                    <li>date of birth</li>
                    <li><b><?php echo $azu["birthday"];?></b></li>
                    <li>joined FATCHIP</li>
                    <li><b><?php echo $azu["employmentstart"];?></b></li>
                </ul>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?php
include "footer.php";
mysqli_close($con);
?>
