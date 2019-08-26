<?php
/**
 * Delete users page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.1
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Delete Users')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-users" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(d__('winry', 'Delete each user in the list below?')) ?></p>

        <ul>
            <?php foreach ($view->get('users') as $user) : ?>
            <li>
                <?php $user->display('name') ?>
                <input type="hidden" name="users[]" value="<?php $user->display('id', 'attr') ?>" />
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(d__('winry', 'Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_users" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
