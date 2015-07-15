<?php
/**
 * Manage Cites
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;

$can_add    = isCurrentUserCan('add_city');
$can_edit   = isCurrentUserCan('edit_city');
$can_delete = isCurrentUserCan('delete_city');
$can_manage = isCurrentUserCan('manage_cites');

$header = new View('header');
$header(array( 'title' => __('Cites') ));
?>

    <?php if ($can_add) : ?>
	<div class="btn-block">
		<a href="<?php echo getPageURL('new-city') ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
	</div>
    <?php endif; ?>

	<table id="table-cites" class="table table-bordered table-hover">

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
                    <a href="<?php echo getPageURL('manage-cites', array( 'action' => 'delete_city', 'id' => $city->get('city_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
