<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\Module;
use yii\helpers\Html;

$module = Module::getInstance();

foreach ($module->galleries as $galleryName => $gallery) {
    ?>
    <div class='col-xs-3'>
        <div class='panel panel-default'>
            <div class='panel-heading'><?= $galleryName ?></div>
            <div class='panel-body'>
                <table class='table table-condensed'>
                    <tr>
                        <td><b>Root: </b></td>
                        <td><?= $gallery['root'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Web Root: </b></td>
                        <td><?= $gallery['webRoot'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Placeholder: </b></td>
                        <td><?= $gallery['placeholder'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Extensions: </b></td>
                        <td><?= $gallery['extensions'] ?></td>
                    </tr>
                </table><?= Html::a("->", ['album/', 'gallery' => $galleryName], ['class' => 'btn btn-primary text-center']) ?>
            </div>
        </div>
    </div>
    <?php
}
?>
