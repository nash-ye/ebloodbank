<?php
/**
 * Delete user page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete User')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-user" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(__('Delete user "%s"?'), $view->get('user')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_user" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
