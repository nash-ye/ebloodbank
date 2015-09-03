<?php
/**
 * Manage Users Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit Users') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getUsersLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-users' ))) ?>
            <?php echo getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-user' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php _e('Name') ?></th>
			<th><?php _e('E-mail') ?></th>
			<th><?php _e('Role') ?></th>
            <th><?php _e('Actions') ?></th>
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
                    <?php echo getEditUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                    <?php echo getDeleteUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                    <?php echo getActivateUserLink(array('id' => $user->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>')) ?>
				</td>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getEditUsersURL(),
            'page_url' => addQueryArgs(getEditUsersURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
