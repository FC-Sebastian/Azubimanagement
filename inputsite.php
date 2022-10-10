<?php

include "functions.php";
$website = new Inputsite();
include "session.php";
$_SESSION["origin"] = $_SERVER["PHP_SELF"];
$azubiid = $website->getRequestParameter("id");
$azubi = new azubi();
$website->evaluateRequest();
$azubi = $website->loadAzubiSetTitle($azubiid);
include "header.php";
?>
    <form action="<?php echo $website->getUrl("inputsite.php") ?>" enctype='multipart/form-data' method="post">
        <input id="hideme" type="hidden" name="id" value="<?php echo $azubiid ?>">
        <div class="azuinfo">
            <h1>
                <?php echo $website->getNameIfNotEmpty($azubi) ?>
            </h1>
            <br>
            <div>
                <label for="name">Full Name: </label>
                <input type="text" name="name" value="<?php echo $azubi->getName() ?>"><br>
            </div>
            <div>
                <label for="birthday">Date of birth: </label>
                <input type="date" name="birthday" value="<?php echo $azubi->getBday() ?>"><br>
            </div>
            <div>
                <label for="email">E-Mail: </label>
                <input type="text" name="email" value="<?php echo $azubi->getEmail() ?>"><br>
            </div>
            <div>
                <label for="githubuser">GitHub username: </label>
                <input type="text" name="githubuser" value="<?php echo $azubi->getGithub() ?>"><br>
            </div>
            <div>
                <label for="employmentstart">Employed since: </label>
                <input type="date" name="employmentstart" value="<?php echo $azubi->getEmploystart() ?>"><br>
            </div>
            <div>
                <label for="pictureurl">Picture: </label>
                <input type="file" name="pictureurl"><br>
            </div>
            <?php if ($website->getRequestParameter("passmismatch") == 1): ?>
                <div>
                    <p><b>Passwords didn't match</b></p>
                </div>
            <?php endif; ?>
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
                    <th colspan="2">
                        <a href="<?php echo $website->getUrl("inputsite.php") ?>">
                            New Azubi</a>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <a href="<?php echo $website->getUrl("teameditsite.php") ?>">
                            Team
                        </a>
                    </th>
                </tr>
            </table>
        </div>
        <div id="passdiv">

        </div>
        <div class="azuskills">
            <div>
                <label for="kskills">Known Skills (seperate by comma)</label>
                <br>
                <textarea name="kskills" rows="5" cols="60"><?php $website->implodeSkills($azubi->getPreskills()) ?></textarea>
            </div>
            <div>
                <label for="nskills">New Skills (seperate by comma)</label>
                <br>
                <textarea name="nskills" rows="5" cols="60"><?php $website->implodeSkills($azubi->getNewskills()) ?></textarea>
            </div>
        </div>
        <div class="buttons">
            <div>
                <input type="submit" value="Send">
            </div>
            <div>
                <?php if (!empty($azubiid)): ?>
                    <label>Delete Azubi?</label>
                    <input type="checkbox" name="delete">
                <?php endif; ?>
            </div>
        </div>
    </form>
<?php
include "footer.php";
?>