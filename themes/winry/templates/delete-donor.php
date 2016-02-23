<?php
/**
 * Delete donor page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete Donor')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-donor" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(__('Delete donor "%s"?'), $view->get('donor')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_donor" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
