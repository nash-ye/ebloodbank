<?php
/**
 * Delete districts page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.1
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Delete Districts')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-delete-districts" class="form-horizontal" method="POST">

        <p><?= EBB\escHTML(__('Delete each district in the list below?')) ?></p>

        <ul>
            <?php foreach ($view->get('districts') as $district) : ?>
            <li>
                <?php $district->display('name') ?>
                <input type="hidden" name="districts[]" value="<?php $district->display('id', 'attr') ?>" />
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-danger"><?= EBB\escHTML(__('Delete')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="delete_districts" />
        <?= EBB\getTokenField(['name' => 'token']) ?>

    </form>

<?php
$view->displayView('footer');
