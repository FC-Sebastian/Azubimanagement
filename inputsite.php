<?php
include "session.php";
$_SESSION["origin"] = $_SERVER["PHP_SELF"];
include "functions.php";
$title = "new Azubi";
$con = getDatabaseConnection();
$azubidata = getAzubiData($con);
$azubiid = getRequestParameter("id");
$curazubidata = [];
$preskills = "";
$newskills = "";
if (!empty($azubiid)){
    $preskills = getSkillArray($con,$azubiid,"pre");
    $preskills = implode(", ",$preskills);
    $newskills = getSkillArray($con,$azubiid,"new");
    $newskills = implode(", ",$newskills);
    $curazubidata = $azubidata[$azubiid-1];
}
include "header.php";
?>
<form action="newazu.php" enctype='multipart/form-data' method="post">
    <input id="hideme" type="hidden" name="id" value="<?php echo $azubiid?>">
    <div class="azuinfo">
        <h1>
            <?php
                if (!empty($azubiid)){
                    echo $curazubidata["name"];
                } else {
                    echo "New Azubi";
                }
            ?>
        </h1>
        <br>
        <div>
            <label for="name">Full Name: </label>
            <input type="text" name="name" value="<?php echo getValueIfIsset($curazubidata,"name")?>"><br>
        </div>
        <div>
            <label for="birthday">Date of birth: </label>
            <input type="date" name="birthday" value="<?php echo getValueIfIsset($curazubidata,"birthday")?>"><br>
        </div>
        <div>
            <label for="email">E-Mail: </label>
            <input type="text" name="email" value="<?php echo getValueIfIsset($curazubidata,"email")?>"><br>
        </div>
        <div>
            <label for="githubuser">GitHub username: </label>
            <input type="text" name="githubuser" value="<?php echo getValueIfIsset($curazubidata,"githubuser")?>"><br>
        </div>
        <div>
            <label for="githubuserlink">GitHublink: </label>
            <input type="text" name="githubuserlink" value="<?php echo getValueIfIsset($curazubidata,"githubuserlink")?>"><br>
        </div>
        <div>
            <label for="employmentstart">Employed since: </label>
            <input type="date" name="employmentstart" value="<?php echo getValueIfIsset($curazubidata,"employmentstart")?>"><br>
        </div>
        <div>
            <label for="pictureurl">Picture: </label>
            <input type="file" name="pictureurl"><br>
        </div>
        <?php if (getRequestParameter("passmismatch") == 1):?>
            <div>
                <p><b>Passwords didn't match</b></p>
            </div>
        <?php endif;?>
        <div>
            <label for="pass">Password: </label>
            <input name="pass" type="password">
        </div>
        <div>
            <label for="confpass">Confirm Password: </label>
            <input name="confpass" type="password">
        </div>
    </div>
    <div class="azubilinks">
        <table id="inputtable">
            <tr>
                <th colspan="2"><a href="http://localhost/inputsite.php">New Azubi</a></th>
            </tr>
            <tr>
            <?php
                $jimmy=0;
                foreach($azubidata as $adata):
                    if ($jimmy === 2):
                        ?>
                        </tr><tr>
                    <?php
                        $jimmy -= 2;
                        endif;
                        ?>
                    <td>
                        <a href="http://localhost/inputsite.php?id=<?php echo $adata["id"]?>"><?php echo $adata["name"]?></a>
                    </td>
                <?php
                $jimmy++;
                endforeach;
                ?>
            <tr>
                <th colspan="2"><a href="http://localhost/teameditsite.php">Team</a></th>
            </tr>
        </table>
    </div>
    <div id="passdiv">

    </div>
    <div class="azuskills">
        <div>
            <label for="kskills">Known Skills (seperate by comma)</label>
            <br>
            <textarea name="kskills" rows="5" cols="60"><?php echo $preskills ?></textarea>
        </div>
        <div>
            <label for="nskills">New Skills (seperate by comma)</label>
            <br>
            <textarea name="nskills" rows="5" cols="60"><?php echo $newskills ?></textarea>
        </div>
    </div>
    <div class="buttons">
        <div>
            <input type="submit" value="Send">
        </div>
        <div>
            <?php if (!empty($azubiid)):?>
            <label>Delete Azubi?</label>
            <input type="checkbox" name="delete">
            <?php endif;?>
        </div>
    </div>
</form>
<?php
include "footer.php";
mysqli_close($con);
?>