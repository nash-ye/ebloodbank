<?php
/**
 * Delete donor page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Delete Donor')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-donor" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(d__('winry', 'Delete donor "%s"?'), $view->get('donor')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_donor" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
