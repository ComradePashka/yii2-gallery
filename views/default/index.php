<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\Module;
use yii\helpers\Html;

echo "test!";

foreach (Module::$galleries as $gallery) {
    ?>
    <div class='col-xs-3'>
        <div class='panel panel-default'>
            <div class='panel-heading'><?= $gallery->name ?></div>
            <div class='panel-body'>
                <table class='table table-condensed'>
                    <tr>
                        <td><b>Root: </b></td>
                        <td><?= $gallery->RootPath ?></td>
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
                </table><?= Html::a("->", ['', 'gallery' => $gallery->name], ['class' => 'btn btn-primary text-center']) ?>
            </div>
        </div>
    </div>
    <?php
}
?>
