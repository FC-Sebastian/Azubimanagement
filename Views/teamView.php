<?php
$allazubi = $controller->getAzubiData();
?>
<div class="row d-flex justify-content-center">
    <div class="col-12">
        <div class="row">
            <?php foreach ($allazubi as $azu):?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-11 gy-3">
                    <div class="card rounded-5 h-100">
                        <div class="card-body text-center">
                            <a href="<?php echo $controller->getUrl("index.php")?>?controller=AzubiSite&id=<?php echo $azu->getId()?>">
                                <img height="275" class="card-img-top rounded-5" src="<?php echo $controller->getPictureUrl($azu->getPictureurl())?>">
                            </a>
                            <h class="card-title"><?php echo $azu->getName();?></h>
                            <div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="mailto:<?php echo $azu->getEmail();?>">
                                            <?php echo $azu->getEmail();?>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <?php echo $azu->getGithubuser();?>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <ul class="list-group list-group-flush"">
                                <li class="list-group-item border-0 border-top">date of birth</li>
                                <li class="list-group-item"><b><?php echo $azu->getBirthday();?></b></li>
                                <li class="list-group-item border-0">joined FATCHIP</li>
                                <li class="list-group-item"><b><?php echo $azu->getEmploymentstart();?></b></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>