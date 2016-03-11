<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\Module;

$module = Module::getInstance();
echo "Hello! test: ". json_encode($module->test) ." test2: $module->test2 test3: $module->test3 params: " . $module->params['foo'];

?>

<div class="gallery-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
<p>
    This is the view content for action "<?= $this->context->action->id ?>".
    The action belongs to the controller "<?= get_class($this->context) ?>"
    in the "<?= $this->context->module->id ?>" module.
</p>
<p>
    You may customize this page by editing the following file:<br>
    <code><?= __FILE__ ?></code>
</p>
</div>
