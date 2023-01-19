<?php
/** @var Edit $controller */
$azubi = $controller->loadAzubi();
?>
<form action="<?php echo $controller->getUrl("index.php")?>?controller=Edit" enctype='multipart/form-data' method="post">
    <input id="id" type="hidden" name="id" value="<?php echo $azubi->getId()?>">
    <div class="row justify-content-center">
        <h1 class="text-start">
            <?php if (!empty($azubi->getName())):?>
                <?php echo $azubi->getName()?>
            <?php else: ?>
                New Azubi
            <?php endif;?>
        </h1>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="name">Name: </label>
                        <input class="p-1 form-control rounded-end" type="text" name="name" value="<?php echo $azubi->getName()?>"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="email">E-Mail: </label>
                        <input class="p-1 form-control rounded-end" type="text" name="email" value="<?php echo $azubi->getEmail()?>"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="githubuser">GitHub username: </label>
                        <input class="p-1 form-control rounded-end" type="text" name="githubuser" value="<?php echo $azubi->getGithubuser()?>"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="pictureurl">Picture: </label>
                        <input class="form-control rounded-end" type="file" name="pictureurl"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="birthday">Date of birth: </label>
                        <input class="p-1 form-control rounded-end" type="date" name="birthday" value="<?php echo $azubi->getBirthday()?>"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="employmentstart">Employed since: </label>
                        <input class="p-1 form-control rounded-end" type="date" name="employmentstart" value="<?php echo $azubi->getEmploymentstart()?>"><br>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="pass">Password: </label>
                        <input class="p-1 form-control" name="pass" type="password">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="input-group">
                        <label class="input-group-text" for="confpass">Confirm Password: </label>
                        <input class="p-1 form-control" name="confpass" type="password">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2 gx-2">
        <div id="ksDiv" class="col-xl-6 col-lg-6 col-md-6 col-12">
            <?php if(empty($azubi->getPreskills())):?>
                <div class="input-group mb-2">
                    <label class="input-group-text">Known Skill:</label>
                    <input class="form-control" name="kskills[]">
                    <button type="button" class="btn btn-outline-primary" onclick="addInput('pre')">+</button>
                    <button type="button" class="btn btn-outline-danger" onclick="removeParent(this)">X</button>
                </div>
            <?php endif;?>
            <?php foreach ($azubi->getPreskills() as $skill):?>
                <div class="input-group mb-2">
                    <label class="input-group-text">Known Skill:</label>
                    <input class="form-control" name="kskills[]" value="<?php echo $skill?>">
                    <button type="button" class="btn btn-outline-primary" onclick="addInput('pre')">+</button>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteSkill('pre','<?php echo $skill?>',this)">X</button>
                </div>
            <?php endforeach;?>
        </div>
        <div id="nsDiv" class="col-xl-6 col-lg-6 col-md-6 col-12">
            <?php if(empty($azubi->getNewskills())):?>
                <div class="input-group mb-2">
                    <label class="input-group-text">New Skill:</label>
                    <input class="form-control" name="nskills[]">
                    <button type="button" class="btn btn-outline-primary" onclick="addInput('new')">+</button>
                    <button type="button" class="btn btn-outline-danger" onclick="removeParent(this)">X</button>
                </div>
            <?php endif;?>
            <?php foreach ($azubi->getNewskills() as $skill):?>
                <div class="input-group mb-2">
                    <label class="input-group-text">New Skill:</label>
                    <input class="form-control" name="nskills[]" value="<?php echo $skill?>">
                    <button type="button" class="btn btn-outline-primary" onclick="addInput('new')">+</button>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteSkill('new','<?php echo $skill?>',this)">X</button>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-start">
            <div>
                <button class="btn btn-outline-primary rounded-3 btn-sm me-2" type="submit" value="updateInsert" name="action">Send</button>
            </div>
            <div>
                <?php if (!empty($azubi->getId())):?>
                    <input type="hidden" name="id" value="<?php echo $azubi->getId()?>">
                    <input class="btn btn-outline-danger rounded-3 btn-sm" type="submit" name="action" value="Delete">
                <?php endif;?>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo $controller->getUrl("js/edit.js")?>"></script>
