<?php
/**
 * Delete user page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Delete User')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-user" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(d__('winry', 'Delete user "%s"?'), $view->get('user')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_user" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
