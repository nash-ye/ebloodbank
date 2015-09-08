<?php
/**
 * Manage Users Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', array( 'title' => __('Edit Users') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getUsersLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-users' ))) ?>
            <?php echo EBB\getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-user' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php __e('Name') ?></th>
			<th><?php __e('E-mail') ?></th>
			<th><?php __e('Role') ?></th>
            <th><?php __e('Actions') ?></th>
		</thead>

		<tbody>

            <?php foreach ($this->get('users') as $user) : ?>

			<tr>
				<td><?php $user->display('id') ?></td>
				<td><?php $user->display('name') ?></td>
				<td><?php $user->display('email') ?></td>
				<td>
                <?php
                    $userRole = $user->getRole();
                    echo ($userRole) ? $userRole->title : $user->get('role');
                ?>
				</td>
				<td>
                    <?php echo EBB\getEditUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                    <?php echo EBB\getDeleteUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                    <?php echo EBB\getActivateUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>')) ?>
				</td>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getEditUsersURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditUsersURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
