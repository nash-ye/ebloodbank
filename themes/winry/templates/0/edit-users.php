<?php
/**
 * Edit users page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Edit Users')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getUsersLink(['content' => d__('winry', 'View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-users']]) ?>
            <?= EBB\getAddUserLink(['content' => d__('winry', 'Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-user']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

    <form id="form-edit-users" method="POST">

        <table id="table-users" class="table table-entities table-bordered table-striped table-hover">

            <thead>
                <th>
                    <input type="checkbox" id="cb-select-all" />
                </th>
                <th><?= EBB\escHTML(d__('winry', 'Name')) ?></th>
                <th><?= EBB\escHTML(d__('winry', 'E-mail')) ?></th>
                <th><?= EBB\escHTML(d__('winry', 'Role')) ?></th>
                <th><?= EBB\escHTML(d__('winry', 'Actions')) ?></th>
            </thead>

            <tbody>

                <?php foreach ($view->get('users') as $user) : ?>

                <tr>
                    <td>
                        <input type="checkbox" name="users[]" value="<?php $user->display('id', 'attr') ?>" class="cb-select" />
                    </td>
                    <td>
                        <?= EBB\getEditUserLink(['user' => $user, 'content' => EBB\escHTML($user->get('name'))]) ?>
                        <?php if ($user->isPending()) : ?>
                            <span class="label label-warning"><?= EBB\escHTML(d__('winry', 'Pending')) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php $user->display('email') ?></td>
                    <td><?= EBB\escHTML($user->getRoleTitle()) ?></td>
                    <td>
                        <?= EBB\getEditUserLink(['user' => $user, 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                        <?= EBB\getDeleteUserLink(['user' => $user, 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                        <?= EBB\getActivateUserLink(['user' => $user, 'content' => '<i class="glyphicon glyphicon-ok"></i>']) ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="btn-group pull-right bulk-actions">
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/delete/users')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i> <?= EBB\escHTML(d__('winry', 'Delete')) ?>
            </button>
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/activate/users')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-ok"></i> <?= EBB\escHTML(d__('winry', 'Activate')) ?>
            </button>
        </div>

    </form>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditUsersURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditUsersURL(), ['page' => '%#%']),
        ])

    ?>

    <script src="<?= EBB\escURL(EBB\getSiteURl('/themes/winry/assets/js/edit-entities.js')) ?>"></script>

<?php
$view->displayView('footer');
