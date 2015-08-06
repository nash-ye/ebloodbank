<?php
/**
 * Manage Cities
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Notices;

$can_edit   = isCurrentUserCan('edit_city');
$can_delete = isCurrentUserCan('delete_city');
$can_manage = isCurrentUserCan('manage_cities');

$header = new View('header', array( 'title' => __('Cities') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('view_cities')) : ?>
		<a href="<?php echo getPageURL('view-cities') ?>" class="btn btn-default btn-manage"><?php _e('View') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_city')) : ?>
		<a href="<?php echo getPageURL('new-city') ?>" class="btn btn-primary btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<?php if ($can_manage) : ?>
				<th><?php _e('Actions') ?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody>

            <?php foreach (EntityManager::getCityRepository()->findAll() as $city) : ?>

            <tr>
                <td><?php $city->display('city_id') ?></td>
                <td><?php $city->display('city_name') ?></td>
                <?php if ($can_manage) : ?>
                <td>
                    <?php if ($can_edit) : ?>
                    <a href="<?php echo getPageURL('edit-city', array( 'id' => $city->get('city_id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
                    <?php endif; ?>
                    <?php if ($can_delete) : ?>
                    <a href="<?php echo getPageURL('manage-cities', array( 'action' => 'delete_city', 'id' => $city->get('city_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
