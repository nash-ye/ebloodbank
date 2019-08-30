<?php
/**
 * View users page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Users')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getEditUsersLink(['content' => d__('winry', 'Edit'), 'atts' => ['class' => 'btn btn-primary btn-edit btn-edit-users']], $context) ?>
            <?= EBB\getAddUserLink(['content' => d__('winry', 'Add New'), 'atts' => ['class' => 'btn btn-default btn-add btn-add-user']], $context) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-users" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<th>#</th>
			<th><?= EBB\escHTML(d__('winry', 'Name')) ?></th>
			<th><?= EBB\escHTML(d__('winry', 'E-mail')) ?></th>
			<th><?= EBB\escHTML(d__('winry', 'Role')) ?></th>
		</thead>

		<tbody>

            <?php foreach ($view->get('users') as $user) : ?>

			<tr>
				<td><?php $user->display('id') ?></td>
				<td>
                    <?php $user->display('name') ?>
                    <?php if ($user->isPending()) : ?>
                        <span class="label label-warning"><?= EBB\escHTML(d__('winry', 'Pending')) ?></span>
                    <?php endif; ?>
                </td>
				<td><?php $user->display('email') ?></td>
				<td><?= EBB\escHTML($user->get('role')) ?></td>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getUsersURL(),
            'page_url' => EBB\addQueryArgs(EBB\getUsersURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
