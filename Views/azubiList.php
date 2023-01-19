<?php
/** @var AzubiList $controller */
$page = $controller->getPage();
$offset = $controller->getOffset();
$pagemax = $controller->getPageMax();
?>
<div>
    <div id="hiddenInputs">
        <input type="hidden" value="<?php echo $page?>" name="page">
        <input type="hidden" value="<?php echo $controller->getRequestParameter("search")?>" name="search">
        <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
        <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
    </div>
    <div class="row justify-content-start">
        <div class="col-lg-4 col-md-6 col-xs-12">
            <form name="searchbarForm" class="mb-3" action="<?php echo $controller->getUrl("index.php?controller=AzubiList")?>" method="post">
                <input type="hidden" value="<?php echo $page?>" name="page">
                <input type="hidden" value="<?php echo $controller->getLimit()?>" name="dropdown">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
                <div class="row position-relative">
                    <div class="col-12 input-group">
                        <input id="search" class="form-control rounded-start" type="search" name="search" autocomplete="off" value="<?php echo $controller->getRequestParameter("search")?>">
                        <input class="btn btn-primary opacity-75 text-opacity-100 rounded-end" type="submit" name="submitsearch" value="Suchen">
                    </div>
                    <div class="col-12">
                        <ul id="searchbarList" class="list-group position-absolute top-100 start-0 px-2 w-100"></ul>
                    </div>
                </div>
            </form>
        </div>
        <div id="tableDiv" class="col-12" onchange="console.log('el change')">
            <div class="row text-center justify-content-center d-md-flex d-none gx-1 bg-primary bg-opacity-50">
                <div class="col-1"></div>
                <div class="col-3 py-1">
                    <a class="link-dark" href="<?php echo $controller->getOrderUrl("name")?>">
                        Name
                    </a>
                </div>
                <div class="col-3 py-1">
                    <a class="link-dark" href="<?php echo $controller->getOrderUrl("birthday")?>">
                        Geburtstag
                    </a>
                </div>
                <div class="col-3 py-1">
                    <a class="link-dark" href="<?php echo $controller->getOrderUrl("email")?>">
                        E-Mail
                    </a>
                </div>
                <div class="col-2"></div>
            </div>
            <form id="tableForm" action="<?php echo $controller->getUrl("index.php?controller=AzubiList")?>" method="post">
                <div class="my-3">
                    <input class="btn btn-outline-danger btn-sm" type="submit" name="deleteselected" value="ausgewählte Azubis löschen">
                    <input type="hidden" name="action"  value="deleteMultiplAzubis">
                </div>
            </form>
        </div>
    </div>
    <div>
        <div class="row justify-content-center mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6 col-8">
                <form method="post">
                    <select id="dropdown" class="form-select" name="dropdown" onchange="this.form.submit()">
                        <?php foreach ($controller->getDropdownOptions() as $option):?>
                            <option id="dropdownOption" value="<?php echo $option?>"<?php if ($option == $controller->getLimit()) {echo " selected='selected'";}?>>
                                <?php echo $option?>
                            </option>
                        <?php endforeach;?>
                    </select>
                    <input type="hidden" name="controller" value="AzubiList">
                </form>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-lg-1 col-md-2 col-6 order-lg-1 order-md-1 order-2">
                <?php if ($page > 1):?>
                    <a href="<?php echo $controller->getPaginationUrl(-1)?>">
                        <img src="<?php echo $controller->getUrl()?>pics/iconmonstr-caret-left-filled.svg">
                    </a>
                <?php endif;?>
            </div>
            <form class="col-lg-10 col-md-8 col-12 order-lg-2 order-md-2 order-1 align-self-center text-center" method="post" action="<?php echo $controller->getUrl("index.php?controller=AzubiList")?>">
                <?php if ($pagemax > 1):?>
                    <?php for ($i = 1; $i <= $pagemax; $i++):?>
                        <input class="btn btn-outline-secondary" name="page" type="submit" value="<?php echo $i?>">
                    <?php endfor;?>
                <?php endif;?>
                <input type="hidden" value="<?php echo $controller->getRequestParameter("search")?>" name="search">
                <input type="hidden" value="<?php echo $controller->getLimit()?>" name="dropdown">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("order")?>" name="order">
                <input type="hidden" value="<?php echo $controller->getRequestParameter("orderdir")?>" name="orderdir">
            </form>
            <div class="col-lg-1 col-md-2 col-6 order-3">
                <?php if ($page < $pagemax):?>
                    <a href="<?php echo $controller->getPaginationUrl()?>">
                        <img src="<?php echo $controller->getUrl()?>pics/iconmonstr-caret-right-filled.svg">
                    </a>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $controller->getUrl("js/list.js")?>"></script>
