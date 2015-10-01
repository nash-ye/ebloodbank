<?php
/**
 * Approve donor page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Approve Donor')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-approve-donor" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(__('Approve donor "%s"?'), $view->get('donor')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Approve')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="approve_donor" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
