<?php

include "classes/Listsite.php";
$website = new Listsite();
include "session.php";
$_SESSION["origin"] = $_SERVER["PHP_SELF"];
$title = $website->getTitle();
include "header.php";
if (!empty($_REQUEST)){
    $website->evaluateRequest();
}
$page = $website->getPage();
$limit = $website->getLimit();
$offset = $website->getOffset();
$azubidata = $website->getAzubiData();
$pagemax2 = $pagemax = $website->getPageMax($limit, $azubidata);
$pagei = $website->getPageI();
$ddoptions = $website->getDd();
$azubidata = $website->getAzubiData(
    false,
    "*",
    $website->getRequestParameter("order"),
    $website->getRequestParameter("orderdir"),
    $website->getRequestParameter("search"),
    $limit,
    $offset
);
?>
<form action="<?php
echo $website->getUrl("teameditsite.php") ?>" method="post">
    <div class="teamedit">
        <div id="searchbar">
            <input id="bar" type="search" name="search">
            <input type="submit" name="submitsearch" value="Suchen">
        </div>
        <table class="edittable">
            <tr class="colored">
                <th class="borderboys" class="header"></th>
                <th class="borderboys" class="header">
                    <a href="<?php
                    echo $website->getUrl("teameditsite.php") ?>?order=name&orderdir=<?php
                    echo($website->getRequestParameter("orderdir", -1) * -1) ?>&page=<?php
                    echo $page ?>&dropdown=<?php
                    echo $limit ?>&search=<?php
                    echo $website->getRequestParameter("search") ?>">
                        Name
                    </a>
                </th>
                <th class="borderboys" class="header">
                    <a href="<?php
                    echo $website->getUrl("teameditsite.php") ?>?order=birthday&orderdir=<?php
                    echo($website->getRequestParameter("orderdir", -1) * -1) ?>&page=<?php
                    echo $page ?>&dropdown=<?php
                    echo $limit ?>&search=<?php
                    echo $website->getRequestParameter("search") ?>">
                        Geburtstag
                    </a>
                </th>
                <th class="borderboys" class="header">
                    <a href="<?php
                    echo $website->getUrl("teameditsite.php") ?>?order=email&orderdir=<?php
                    echo($website->getRequestParameter("orderdir", -1) * -1) ?>&page=<?php
                    echo $page ?>&dropdown=<?php
                    echo $limit ?>&search=<?php
                    echo $website->getRequestParameter("search") ?>">
                        E-Mail
                    </a>
                </th>
                <th class="borderboys" class="header"></th>
            </tr>
            <?php
            foreach ($azubidata as $azubidata): ?>
                <tr class="colored">
                    <td class="borderboys">
                        <input type="checkbox" name="deletearray[]" value="<?php
                        echo $azubidata->getId() ?>">
                    </td>
                    <td class="borderboys" class="textboy">
                        <?php
                        echo $azubidata->getName();
                        ?>
                    </td>
                    <td class="borderboys" class="textboy">
                        <?php
                        echo $azubidata->getBday();
                        ?>
                    </td>
                    <td class="borderboys" class="textboy">
                        <?php
                        echo $azubidata->getEmail();
                        ?>
                    </td>
                    <td class="borderboys" class="textboy">
                        <a class="linkpic" href="<?php
                        echo $website->getUrl("inputsite.php") ?>?id=<?php
                        echo $azubidata->getId() ?>">
                            <img src="<?php
                            echo $website->getUrl("") ?>pics/iconmonstr-pencil-14.svg">
                        </a>
                        <a class="linkpic" href="teameditsite.php?delete=<?php
                        echo $azubidata->getId() ?>&dropdown=<?php
                        echo $limit ?>">
                            <img src="<?php
                            echo $website->getUrl("") ?>pics/iconmonstr-trash-can-29.svg">
                        </a>
                    </td>
                </tr>
                <?php
                $offset++; endforeach; ?>
        </table>
        <table class="navtable">
            <tr>
                <td>
                    <input id="del" type="submit" name="deleteselected" value="ausgewählte Azubis löschen">
                </td>
                <td colspan="2">
                    <select name="dropdown" id="dropdown">
                        <?php
                        foreach ($ddoptions as $option): ?>
                            <option value="<?php
                            echo $option ?>"<?php
                            if ($option == $limit) {
                                echo " selected='selected'";
                            } ?>>
                                <?php
                                echo $option ?>
                            </option>
                        <?php
                        endforeach; ?>
                    </select>
                    <input name="ddsubmit" type="submit" value="Aktualisieren">
                </td>
                <td>
                    <input id="new" type="submit" name="newazubi" value="Neuen Azubi anlegen">
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if ($page >= 2): ?>
                        <a href="<?php
                        echo $website->getUrl("teameditsite.php") ?>?page=<?php
                        echo($page - 1) ?>&order=<?php
                        echo $website->getRequestParameter("order") ?>&orderdir=<?php
                        echo $website->getRequestParameter("orderdir", 0) ?>&dropdown=<?php
                        echo $limit ?>&search=<?php
                        echo $website->getRequestParameter("search") ?>">
                            <img id="left" src="<?php
                            echo $website->getUrl("") ?>pics/iconmonstr-caret-left-filled.svg">
                        </a>
                    <?php
                    endif; ?>
                </td>
                <td colspan="2">
                    <?php
                    if ($pagemax > 1):
                        while ($pagemax > 0):
                            ?>
                            <input class="pageselect" name="pageselect" type="submit" value="<?php
                            echo $pagei ?>">
                            <?php
                            $pagei++;
                            $pagemax--;
                        endwhile;
                    endif;
                    ?>
                </td>
                <td>
                    <?php
                    if ($page < $pagemax2): ?>
                        <a href="<?php
                        echo $website->getUrl("teameditsite.php") ?>?page=<?php
                        echo($page + 1) ?>&order=<?php
                        echo $website->getRequestParameter("order") ?>&orderdir=<?php
                        echo $website->getRequestParameter("orderdir", 0) ?>&dropdown=<?php
                        echo $limit ?>&search=<?php
                        echo $website->getRequestParameter("search") ?>">
                            <img id="right" src="<?php
                            echo $website->getUrl("") ?>pics/iconmonstr-caret-right-filled.svg">
                        </a>
                    <?php
                    endif; ?>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <input type="hidden" value="<?php
        echo $page ?>" name="page">
        <input type="hidden" value="<?php
        echo $website->getRequestParameter("search") ?>" name="lastsearch">
        <input type="hidden" value="<?php
        echo $website->getRequestParameter("order") ?>" name="order">
        <input type="hidden" value="<?php
        echo $website->getRequestParameter("orderdir") ?>" name="orderdir">
    </div>
</form>
<?php
include "footer.php";
?>
