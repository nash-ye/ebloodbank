<?php
/**
 * Manage Districts
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Notices;

$can_edit   = isCurrentUserCan('edit_district');
$can_delete = isCurrentUserCan('delete_district');
$can_manage = isCurrentUserCan('manage_districts');

$header = new View('header', array( 'title' => __('Districts') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('view_districts')) : ?>
		<a href="<?php echo getPageURL('view-districts') ?>" class="btn btn-default btn-manage"><?php _e('View') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_district')) : ?>
		<a href="<?php echo getPageURL('new-district') ?>" class="btn btn-primary btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

	<table id="table-districts" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('City') ?></th>
				<?php if ($can_manage) : ?>
				<th><?php _e('Actions') ?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody>

            <?php foreach (EntityManager::getDistrictRepository()->findAll() as $distr) : ?>

            <tr>
                <td><?php $distr->display('distr_id') ?></td>
                <td><?php $distr->display('distr_name') ?></td>
                <td>
                    <?php EntityManager::getCityRepository()->find($distr->get('distr_city_id'))->display('city_name') ?>
                </td>
                <?php if ($can_manage) : ?>
                <td>
                    <?php if ($can_edit) : ?>
                    <a href="<?php echo getPageURL('edit-district', array( 'id' => $distr->get('distr_id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
                    <?php endif; ?>
                    <?php if ($can_delete) : ?>
                    <a href="<?php echo getPageURL('manage-districts', array( 'action' => 'delete_district', 'id' => $distr->get('distr_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
