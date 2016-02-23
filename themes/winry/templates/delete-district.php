<?php
/**
 * Delete district page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete District')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-district" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(__('Delete district "%s"?'), $view->get('district')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_district" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
