<?php

session_start();
include "classes/Loginsite.php";
$website = new Loginsite();
$title = $website->getTitle();
$hashpass = $website->getHashedPass();
if ($website->validateAzubiLogin($website->getRequestParameter("loginemail"), $hashpass)) {
    $_SESSION["logintime"] = time();
    if (isset($_SESSION["origin"])) {
        header("location: " . $website->getUrl("") . str_replace("azubimanagement/", "", $_SESSION["origin"]));
    } else {
        header("location: " . $website->getUrl("teameditsite.php"));
    }
}
include "header.php";
?>
<div id="logindiv">
    <form method="post" action="<?php
    echo $website->getUrl("loginsite.php") ?>">
        <table id="logintable">
            <tr>
                <th colspan="2">
                    Login:
                </th>
            </tr>
            <tr>
                <td>
                    <label for="loginemail">E-Mail:</label>
                </td>
                <td>
                    <input id="loginemail" name="loginemail" type="text">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="loginpass">Password:</label>
                </td>
                <td>
                    <input id="loginpass" name="loginpass" type="password">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input name="loginsubmit" type="submit" value="login">
                </td>
            </tr>
        </table>
    </form>
</div>
<?php
include "footer.php";
?>
