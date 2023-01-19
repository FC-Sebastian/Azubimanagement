<?php
/** @var Lists $controller */
$modelData = $controller->getRequestData();
$page = $controller->getPage();
$pagemax = $controller->getPageMax();
?>
<div>
    <form action="<?php echo $controller->getUrl("index.php?")?>" method="post">
        <input type="hidden" name="controller" value="<?php echo get_class($controller)?>">
        <input type="hidden" value="<?php echo $page?>" name="page">
        <input type="hidden" value="<?php echo $controller->getLimit()?>" name="dropdown">
        <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
        <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
        <input type="search" name="search">
        <input type="submit" name="submitsearch" value="Suchen">
    </form>
    <table class="table table-borderless table-striped table-secondary">
        <?php foreach ($controller->getHeaders() as $header):?>
            <th>
                <a href="<?php echo $controller->getOrderUrl($header)?>"><?php echo ucwords($header)?></a>
            </th>
        <?php endforeach;?>
        <?php foreach ($modelData as $data):?>
            <tr>
                <?php foreach ($controller->getHeaders() as $header):?>
                    <td>
                        <?php $getString = "get".$header;?>
                        <?php echo $data->$getString()?>
                    </td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
    </table>
    <table>
        <tr>
            <td>
                <?php if ($page > 1):?>
                    <a href="<?php echo $controller->getPaginationUrl(-1)?>">
                        <img src="<?php echo $controller->getUrl()?>pics/iconmonstr-caret-left-filled.svg">
                    </a>
                <?php endif;?>
            </td>
            <form method="post" action="<?php echo $controller->getUrl("index.php")?>">
                <input type="hidden" value="<?php echo get_class($controller)?>" name="controller">
                <input type="hidden" value="<?php echo $controller->getLimit()?>" name="dropdown">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("search")?>" name="search">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
                <td colspan="2">
                    <?php if ($pagemax > 1):?>
                        <?php for ($i = 1; $i <= $pagemax; $i++):?>
                            <input name="page" type="submit" value="<?php echo $i?>">
                        <?php endfor;?>
                    <?php endif;?>
                </td>
            </form>
            <td>
                <?php if ($page < $pagemax):?>
                    <a href="<?php echo $controller->getPaginationUrl()?>">
                        <img src="<?php echo $controller->getUrl()?>pics/iconmonstr-caret-right-filled.svg">
                    </a>
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <form action="<?php echo $controller->getUrl("index.php")?>" method="post">
                <select name="dropdown">
                    <?php foreach ($controller->getDropdownOptions() as $option):?>
                        <option value="<?php echo $option?>"<?php if ($option == $controller->getLimit()) {echo " selected='selected'";}?>>
                            <?php echo $option?>
                        </option>
                    <?php endforeach;?>
                </select>
                <input type="hidden" value="<?php echo $controller->getRequestParameter("search")?>" name="search">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
                <button type="submit" name="controller" value="<?php echo get_class($controller)?>">Aktualisieren</button>
            </form>
        </tr>
    </table>
    <form method="post" action="<?php echo $controller->getUrl("index.php")?>">
        <button type="submit" name="controller" value="DynamicAzubiList">Azubis</button>
        <button type="submit" name="controller" value="BerufsschulList">Berufsschule</button>
        <button type="submit" name="controller" value="BerufsschulklasseList">Berufsschulklasse</button>
    </form>
</div>
