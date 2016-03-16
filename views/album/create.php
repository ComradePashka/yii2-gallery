<?php
use comradepashka\gallery\Module;
?>
<div class="post-create">
    <?= $this->render('_form', ['gallery' => Module::$gallery->name, 'currentPath' => Module::$currentPath, 'name' => '']) ?>
</div>