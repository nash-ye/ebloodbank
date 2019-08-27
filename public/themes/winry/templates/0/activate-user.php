<?php
/**
 * Activate user page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Activate User')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-activate-user" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(sprintf(d__('winry', 'Activate user "%s"?'), $view->get('user')->get('name'))) ?></p>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Activate')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="activate_user" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
