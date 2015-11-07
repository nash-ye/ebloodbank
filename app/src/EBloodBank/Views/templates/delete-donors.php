<?php
/**
 * Delete donors page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.1
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete Donors')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-donors" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(__('Delete each donor in the list below?')) ?></p>

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
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_donors" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
