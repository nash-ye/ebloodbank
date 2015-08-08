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
use EBloodBank\Kernal\Notices;

$users = EntityManager::getUserRepository()->findAll();

$header = new View('header', array( 'title' => __('Users') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('manage_users')) : ?>
		<a href="<?php echo getPageURL('manage-users') ?>" class="btn btn-primary btn-manage"><?php _e('Manage') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_user')) : ?>
		<a href="<?php echo getPageURL('new-user') ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

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
$footer = new View('footer');
$footer();
