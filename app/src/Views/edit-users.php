<?php
/**
 * Manage Users Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;

$limit = Options::getOption('entities_per_page');
$pageNumber = max((int) $this->get('page'), 1);
$offset = ($pageNumber - 1) * $limit;

$userRepository = EntityManager::getUserRepository();
$users = $userRepository->findBy(array(), array(), $limit, $offset);

View::display('header', array( 'title' => __('Users') ));
?>

	<div class="btn-block">
        <?php echo getUsersLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-users' ))) ?>
        <?php echo getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-user' ))) ?>
	</div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php _e('Logon') ?></th>
			<th><?php _e('Role') ?></th>
            <th><?php _e('Actions') ?></th>
		</thead>

		<tbody>

            <?php foreach ($users as $user) : ?>

			<tr>
				<td><?php $user->display('id') ?></td>
				<td><?php $user->display('logon') ?></td>
				<td>
                <?php
                    $userRole = $user->getRole();
                    echo ($userRole) ? $userRole->title : $user->get('role');
                ?>
				</td>
				<td>
                    <?php echo getEditUserLink(array('id' => $user->get('id'), 'content' => '<i class="fa fa-pencil"></i>')) ?>
                    <?php echo getDeleteUserLink(array('id' => $user->get('id'), 'content' => '<i class="fa fa-trash"></i>')) ?>
                    <?php echo getApproveUserLink(array('id' => $user->get('id'), 'content' => '<i class="fa fa-check"></i>')) ?>
				</td>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total' => (int) ceil($userRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getEditUsersURL(), array( 'page' => '%#%' )),
            'base_url' => getEditUsersURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
