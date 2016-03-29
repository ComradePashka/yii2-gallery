<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\Module;
use yii\helpers\Html;

foreach (Module::getInstance()->galleries as $name => $gallery) {
    Module::$galleryName = $name;
    ?>
    <div class='col-xs-4'>
        <div class='panel panel-default'>
            <div class='panel-heading'><?= Module::getGallery()->name ?></div>
            <div class='panel-body'>
                <table class='table table-condensed'>
                    <tr>
                        <td><b>Root: </b></td>
                        <td><?= Module::getGallery()->RootPath ?></td>
                    </tr>
                    <tr>
                        <td><b>Web Root: </b></td>
                        <td><?= Module::getGallery()->WebPath ?></td>
                    </tr>
                    <tr>
                        <td><b>Placeholder: </b></td>
                        <td><?= Module::getGallery()->placeholder ?></td>
                    </tr>
                    <tr>
                        <td><b>Extensions: </b></td>
                        <td><?= Module::getGallery()->extensions ?></td>
                    </tr>
                </table><?= Html::a("->", ['image/index', 'galleryName' => Module::$galleryName], ['class' => 'btn btn-primary text-center']) ?>
            </div>
        </div>
    </div>

    <?php
}
?>