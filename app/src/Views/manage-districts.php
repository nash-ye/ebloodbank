<?php
/**
 * Manage Districts
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;
use eBloodBank\Models\Cites;
use eBloodBank\Models\Districts;

$can_add    = isCurrentUserCan('add_distr');
$can_edit   = isCurrentUserCan('edit_distr');
$can_delete = isCurrentUserCan('delete_distr');
$can_manage = isCurrentUserCan('manage_distrs');

$header = new View('header');
$header(array( 'title' => __('Districts') ));
?>

	<?php if ($can_add) : ?>
	<div class="btn-block">
		<a href="<?php echo getSiteURL(array( 'page' => 'new-distr' )) ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
	</div>
	<?php endif; ?>

	<table id="table-distrs" class="table table-bordered table-hover">

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

			<?php foreach (Districts::fetchAll() as $distr) : ?>

				<tr>
					<td><?php $distr->display('distr_id') ?></td>
					<td><?php $distr->display('distr_name') ?></td>
					<td>
						<?php

							$city = Cites::fetchByID($distr->get('distr_city_id'));
							$city->display('city_name');

						?>
					</td>
					<?php if ($can_manage) : ?>
					<td>
						<?php if ($can_edit) : ?>
						<a href="<?php echo getSiteURL(array( 'page' => 'edit-distr', 'id' => $distr->get('distr_id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
						<?php endif; ?>
						<?php if ($can_delete) : ?>
						<a href="<?php echo getSiteURL(array( 'page' => 'manage-distrs', 'action' => 'delete_distr', 'id' => $distr->get('distr_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
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
