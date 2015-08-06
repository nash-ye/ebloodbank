<?php
/**
 * Manage Users
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Notices;

$can_edit    = isCurrentUserCan('edit_user');
$can_delete  = isCurrentUserCan('delete_user');
$can_manage  = isCurrentUserCan('manage_users');
$can_approve = isCurrentUserCan('approve_user');

$users = EntityManager::getUserRepository()->findAll();

$header = new View('header', array( 'title' => __('Users') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('view_users')) : ?>
		<a href="<?php echo getPageURL('view-users') ?>" class="btn btn-default btn-manage"><?php _e('View') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_user')) : ?>
		<a href="<?php echo getPageURL('new-user') ?>" class="btn btn-primary btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

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

            <?php foreach ($users as $user) : ?>

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
                    <?php if ($can_delete && $user->get('user_id') !== getCurrentUserID()) : ?>
                    <a href="<?php echo getPageURL('manage-users', array( 'action' => 'delete_user', 'id' => $user->get('user_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
                    <?php endif; ?>
                    <?php if ($can_approve && $user->isPending()) : ?>
                    <a href="<?php echo getPageURL('manage-users', array( 'action' => 'approve_user', 'id' => $user->get('user_id') )) ?>" class="approve-link"><i class="fa fa-check"></i></a>
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
