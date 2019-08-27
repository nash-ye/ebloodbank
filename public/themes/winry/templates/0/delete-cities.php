<?php
/**
 * Delete cities page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Delete Cities')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-cities" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(d__('winry', 'Delete each city in the list below?')) ?></p>

        <ul>
            <?php foreach ($view->get('cities') as $city) : ?>
            <li>
                <?php $city->display('name') ?>
                <input type="hidden" name="cities[]" value="<?php $city->display('id', 'attr') ?>" />
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_cities" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
