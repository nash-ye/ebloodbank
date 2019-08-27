<?php
/**
 * Delete donors page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Delete Donors')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-donors" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(d__('winry', 'Delete each donor in the list below?')) ?></p>

        <ul>
            <?php foreach ($view->get('donors') as $donor) : ?>
            <li>
                <?php $donor->display('name') ?>
                <input type="hidden" name="donors[]" value="<?php $donor->display('id', 'attr') ?>" />
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_donors" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
