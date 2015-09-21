<?php
/**
 * View Users Page
 *
 * @package    EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Users')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getEditUsersLink(['content' => __('Edit'), 'atts' => ['class' => 'btn btn-primary btn-edit btn-edit-users']]) ?>
            <?= EBB\getAddUserLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-default btn-add btn-add-user']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-users" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<th>#</th>
			<th><?= EBB\escHTML(__('Name')) ?></th>
			<th><?= EBB\escHTML(__('E-mail')) ?></th>
			<th><?= EBB\escHTML(__('Role')) ?></th>
		</thead>

		<tbody>

            <?php foreach ($view->get('users') as $user) : ?>

			<tr>
				<td><?php $user->display('id') ?></td>
				<td><?php $user->display('name') ?></td>
				<td><?php $user->display('email') ?></td>
				<td><?= EBB\escHTML($user->getRoleTitle()) ?></td>
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
