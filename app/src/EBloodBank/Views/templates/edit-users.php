<?php
/**
 * Edit users page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Edit Users')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getUsersLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-users']]) ?>
            <?= EBB\getAddUserLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-user']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-users" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<th>#</th>
			<th><?= EBB\escHTML(__('Name')) ?></th>
			<th><?= EBB\escHTML(__('E-mail')) ?></th>
			<th><?= EBB\escHTML(__('Role')) ?></th>
            <th><?= EBB\escHTML(__('Actions')) ?></th>
		</thead>

		<tbody>

            <?php foreach ($view->get('users') as $user) : ?>

			<tr>
				<td><?php $user->display('id') ?></td>
				<td>
                    <?= EBB\getEditUserLink(['id' => $user->get('id'), 'content' => EBB\escHTML($user->get('name'))]) ?>
                    <?php if ($user->isPending()) : ?>
                        <span class="label label-warning"><?= EBB\escHTML(__('Pending')) ?></span>
                    <?php endif; ?>
                </td>
				<td><?php $user->display('email') ?></td>
				<td><?= EBB\escHTML($user->getRoleTitle()) ?></td>
				<td>
                    <?= EBB\getEditUserLink(['id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                    <?= EBB\getDeleteUserLink(['id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                    <?= EBB\getActivateUserLink(['id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>']) ?>
				</td>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditUsersURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditUsersURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
