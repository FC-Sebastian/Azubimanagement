<?php
include "functions.php";
include "session.php";
$_SESSION["origin"] = $_SERVER["PHP_SELF"];
$title = "back-end liste";
include "header.php";
$con = getDatabaseConnection();
$page = getRequestParameter("page",1);
if ($page < 1){
    $page = 1;
}
$limit = getRequestParameter("dropdown",10);
$offset = ($page - 1) * $limit;
if ($offset < 0){
    $offset = 0;
}
$azubidata = getAzubiData($con);
$pagemax = getPageMax($limit,$azubidata);
$pagei = 1;
$ddoptions = [1,5,10,20];
$azubidata = getAzubiData($con,false,"*",getRequestParameter("order"),getRequestParameter("orderdir"),getRequestParameter("search"),$limit,$offset);
$pagemax2 = $pagemax;
?>
<form action="<?php echo getUrl("teamedit.php")?>" method="post">
    <div class="teamedit">
        <div id="searchbar">
            <input id="bar" type="search" name="search">
            <input type="submit" name="submitsearch" value="Suchen">
        </div>
        <table class="edittable" cellspacing="0">
            <tr class="colored">
                <th class="borderboys" class="header"></th>
                <th class="borderboys" class="header">
                    <a href="<?php echo getUrl("teameditsite.php")?>?order=name&orderdir=<?php echo (getRequestParameter("orderdir",-1) * -1)?>&page=<?php echo $page?>&dropdown=<?php echo $limit?>&search=<?php echo getGetParameter("search")?>">
                        Name
                    </a>
                </th>
                <th class="borderboys" class="header">
                    <a href="<?php echo getUrl("teameditsite.php")?>?order=birthday&orderdir=<?php echo (getRequestParameter("orderdir",-1) * -1)?>&page=<?php echo $page?>&dropdown=<?php echo $limit?>&search=<?php echo getGetParameter("search")?>">
                        Geburtstag
                    </a>
                </th>
                <th class="borderboys" class="header">
                    <a href="<?php echo getUrl("teameditsite.php")?>?order=email&orderdir=<?php echo (getRequestParameter("orderdir",-1) * -1)?>&page=<?php echo $page?>&dropdown=<?php echo $limit?>&search=<?php echo getGetParameter("search")?>">
                        E-Mail
                    </a>
                </th>
                <th class="borderboys" class="header"></th>
            </tr>
            <?php foreach($azubidata as $azubidata):?>
            <tr class="colored">
                <td class="borderboys">
                    <input type="checkbox" name="deletearray[]" value="<?php echo getValueIfIsset($azubidata,"id")?>">
                </td>
                <td class="borderboys" class="textboy">
                    <?php
                        echo getValueIfIsset($azubidata,"name");
                    ?>
                </td>
                <td class="borderboys" class="textboy">
                    <?php
                        echo getValueIfIsset($azubidata,"birthday");
                    ?>
                </td>
                <td class="borderboys" class="textboy">
                    <?php
                        echo getValueIfIsset($azubidata,"email");
                    ?>
                </td>
                <td class="borderboys" class="textboy">
                    <a class="linkpic" href="<?php echo getUrl("inputsite.php")?>?id=<?php echo getValueIfIsset($azubidata,"id")?>">
                        <img src="<?php echo getUrl("")?>pics/iconmonstr-pencil-14.svg">
                    </a>
                    <a class="linkpic" href="teamedit.php?delete=<?php echo getValueIfIsset($azubidata,"id")?>&dropdown=<?php echo $limit?>">
                        <img src="<?php echo getUrl("")?>pics/iconmonstr-trash-can-29.svg">
                    </a>
                </td>
            </tr>
            <?php $offset++; endforeach;?>
        </table>
        <table class="navtable">
            <tr>
                <td>
                    <input id="del" type="submit" name="deleteselected" value="ausgewählte Azubis löschen">
                </td>
                <td colspan="2">
                    <select name="dropdown" id="dropdown">
                        <?php foreach ($ddoptions as $option):?>
                            <option value="<?php echo $option?>"<?php if ($option == $limit){echo " selected='selected'";}?>>
                                <?php echo $option?>
                            </option>
                        <?php endforeach;?>
                    </select>
                    <input name="ddsubmit" type="submit" value="Aktualisieren">
                </td>
                <td>
                    <input id="new" type="submit" name="newazubi" value="Neuen Azubi anlegen">
                </td>
            </tr>
            <tr>
                <td>
                    <?php if ($page >= 2):?>
                        <a href="<?php echo getUrl("teameditsite.php")?>?page=<?php echo ($page - 1)?>&order=<?php echo getRequestParameter("order")?>&orderdir=<?php echo getRequestParameter("orderdir",0)?>&dropdown=<?php echo $limit?>&search=<?php echo getGetParameter("search")?>">
                            <img id="left" src="<?php echo getUrl("")?>pics/iconmonstr-caret-left-filled.svg">
                        </a>
                    <?php endif;?>
                </td>
                <td colspan="2">
                    <?php
                    if ($pagemax > 1):
                        while($pagemax > 0):
                            ?>
                            <input class="pageselect" name="pageselect" type="submit" value="<?php echo $pagei?>">
                            <?php
                            $pagei++;
                            $pagemax--;
                        endwhile;
                    endif;
                    ?>
                </td>
                <td>
                    <?php if ($page < $pagemax2):?>
                        <a href="<?php echo getUrl("teameditsite.php")?>?page=<?php echo ($page + 1)?>&order=<?php echo getRequestParameter("order")?>&orderdir=<?php echo getRequestParameter("orderdir",0)?>&dropdown=<?php echo $limit?>&search=<?php echo getGetParameter("search")?>">
                            <img id="right" src="<?php echo getUrl("")?>pics/iconmonstr-caret-right-filled.svg">
                        </a>
                    <?php endif;?>
                </td>
            </tr>
        </table>
        </div>
        <div>
            <input type="hidden" value="<?php echo $page?>" name="page">
            <input type="hidden" value="<?php echo getRequestParameter("search")?>" name="lastsearch">
            <input type="hidden" value="<?php echo getRequestParameter("order")?>" name="order">
            <input type="hidden" value="<?php echo getRequestParameter("orderdir")?>" name="orderdir">
        </div>
    </form>
<?php
include "footer.php";
mysqli_close($con)
?>
