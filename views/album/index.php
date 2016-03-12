<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\Module;

$module = Module::getInstance();

echo "G: $gallery<br />";
echo "gplace: ";
print_r($module->galleries[$gallery]['placeholder']);
?>