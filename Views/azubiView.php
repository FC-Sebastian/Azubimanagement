<?php
$azubidata = $controller->getRequestData();
if (empty($azubidata->getId())){
    throw new Exception("DIE ANGEGEBENE AZUBI-ID IST NICHT VERGEBEN!");
}
?>
<div">
    <div class="row text-xl-start text-lg-start text-md-start text-center">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <img height="400" width="400" class="img-fluid rounded-5" src=<?php echo $controller->getPictureUrl($azubidata->getPictureurl())?>>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-12 me-auto d-flex flex-column">
            <h1><?php echo $azubidata->getName()?></h1>
            <div class="mb-auto">
                <h2 class="fs-6 fw-normal">Azubi zum Fachinformatiker Anwendungsentwicklung</h2>
            </div>
            <div class="mt-5">
                <p>
                    Geb.: <?php echo $azubidata->getBirthday();?>
                </p>
                <p>
                    E-Mail:
                    <a href= <?php echo "mailto:" . $azubidata->getEmail()?>><?php echo $azubidata->getEmail()?></a>
                </p>
                <p>
                    GitHub: <?php echo $azubidata->getGithubuser()?>
                </p>
                <p>
                    <?php echo $controller->timeSince()?>
                </p>
            </div>
        </div>
        <a class="col-xl-2 d-xl-inline d-none" href="https://www.fatchip.de/" target="_blank">
            <img class="img-fluid" src="<?php echo $controller->getUrl("pics/fatchip-logo.svg")?>" alt="Logo fehlt">
        </a>
    </div>
    <div class="border-top my-1">
        <ol class="list-group list-group-numbered">
            <h3>Vorkenntnisse in Programmierung:</h3>
            <?php foreach ($azubidata->getPreskills() as $skill):?>
                <li class="list-group-item border-start-0 border-end-0">
                    <?php echo $skill;?>
                </li>
            <?php endforeach;?>
        </ol>
        <ul class="list-group list-group-flush">
            <h4>Bei Fatchip bisher gelernt:</h4>
            <?php foreach ($azubidata->getNewskills() as $skill):?>
                <li class="list-group-item">
                    <?php echo $skill;?>
                </li>
            <?php endforeach;?>
        </ul>
        <div class="row">
            <form class="col align-self-start" action="<?php $controller->getUrl("index.php")?>" method="post">
                <input type="hidden" name="controller" value="Edit">
                <input type="hidden" name="id" value="<?php echo $azubidata->getId()?>">
                <input class="btn btn-outline-secondary rounded-3 btn-sm" type="submit" name="greg" value="edit">
            </form>
            <div class="col align-self-end text-end">
                <span><?php echo date("F j, Y")?></span>
                <span id="time"><?php echo date("G:i:s")?></span>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $controller->getUrl("js/time.js")?>"></script>
