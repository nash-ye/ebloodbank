<?php
/**
 * Manage Users
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\EntityManager;
use eBloodBank\SessionManage;
use eBloodBank\Kernal\View;

$can_add    = isCurrentUserCan('add_user');
$can_edit   = isCurrentUserCan('edit_user');
$can_delete = isCurrentUserCan('delete_user');
$can_manage = isCurrentUserCan('manage_users');

$header = new View('header');
$header(array( 'title' => __('Users') ));
?>

    <?php if ($can_add) : ?>
	<div class="btn-block">
		<a href="<?php echo getPageURL('new-user') ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
	</div>
    <?php endif; ?>

	<table id="table-users" class="table table-bordered table-hover">

		<thead>
			<th>#</th>
			<th><?php _e('Logon') ?></th>
			<th><?php _e('Role') ?></th>
            <?php if ($can_manage) : ?>
			<th><?php _e('Actions') ?></th>
            <?php endif; ?>
		</thead>

		<tbody>

            <?php foreach (EntityManager::getUserRepository()->findAll() as $user) : ?>

			<tr>
				<td><?php $user->display('user_id') ?></td>
				<td><?php $user->display('user_logon') ?></td>
				<td>
                <?php
                    $user_role = $user->getRole();
                    echo ($user_role) ? $user_role->title : $user->get('user_role');
                ?>
				</td>
				<?php if ($can_manage) : ?>
				<td>
                    <?php if ($can_edit) : ?>
                    <a href="<?php echo getPageURL('edit-user', array( 'id' => $user->get('user_id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
                    <?php endif; ?>
                    <?php if ($can_delete && $user->get('user_id') !== SessionManage::getCurrentUserID()) : ?>
                    <a href="<?php echo getPageURL('manage-users', array( 'action' => 'delete_user', 'id' => $user->get('user_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
                    <?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

<?php
$footer = new View('footer');
$footer();
