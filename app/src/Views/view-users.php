<?php
/**
 * View Users Page
 *
 * @package    EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Users') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getEditUsersLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-users' ))) ?>
            <?php echo getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-user' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php _e('Name') ?></th>
			<th><?php _e('E-mail') ?></th>
			<th><?php _e('Role') ?></th>
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
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getUsersURL(),
            'page_url' => addQueryArgs(getUsersURL(), array( 'page' => '%#%' )),
        ));

    ?>

<?php
View::display('footer');
