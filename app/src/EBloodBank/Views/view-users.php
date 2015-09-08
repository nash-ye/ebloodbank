<?php
/**
 * View Users Page
 *
 * @package    EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', array( 'title' => __('Users') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getEditUsersLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-users' ))) ?>
            <?php echo EBB\getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-user' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php __e('Name') ?></th>
			<th><?php __e('E-mail') ?></th>
			<th><?php __e('Role') ?></th>
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

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getUsersURL(),
            'page_url' => EBB\addQueryArgs(EBB\getUsersURL(), array( 'page' => '%#%' )),
        ));

    ?>

<?php
View::display('footer');
