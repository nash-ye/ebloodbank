<?php
/**
 * Delete city page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete City')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-city" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(__('Delete city "%s"?'), $view->get('city')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_city" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
