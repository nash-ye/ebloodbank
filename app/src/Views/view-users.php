<?php
/**
 * View Users Page
 *
 * @package    EBloodBank
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
        <?php echo getEditUsersLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-users' ))) ?>
        <?php echo getAddUserLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-user' ))) ?>
	</div>

    <?php View::display('notices') ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php _e('Logon') ?></th>
			<th><?php _e('Role') ?></th>
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
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total' => (int) ceil($userRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getUsersURL(), array( 'page' => '%#%' )),
            'base_url' => getUsersURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
