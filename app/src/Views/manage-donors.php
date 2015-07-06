<?php
/**
 * Manage Donors
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;
use eBloodBank\Models\Donor;
use eBloodBank\Models\Donors;
use eBloodBank\Models\Cites;
use eBloodBank\Models\Districts;

$data['filter_args'] = array_merge(array(
	'name'        => '',
	'status'      => 'all',
	'blood_group' => 'all',
	'distr_id'    => -1,
	'city_id'     => -1,
), $data['filter_args']);

$can_add     = isCurrentUserCan('add_donor');
$can_edit    = isCurrentUserCan('edit_donor');
$can_delete  = isCurrentUserCan('delete_donor');
$can_manage  = isCurrentUserCan('manage_donors');
$can_approve = isCurrentUserCan('approve_donor');

$header = new View('header');
$header(array( 'title' => __('Donors') ));
?>

	<?php if ($can_add) : ?>
	<div class="btn-block">
		<a href="<?php echo getSiteURL(array( 'page' => 'new-donor' )) ?>" class="btn btn-default btn-add-new"><?php _e('Add New') ?></a>
	</div>
	<?php endif; ?>

	<form class="form-inline" action="<?php echo getSiteURL(array( 'page' => 'donors' )) ?>" method="POST">

		<div class="form-group">
			<label>
				<?php _e('Name') ?>
				<input type="text" name="name"  class="form-control" value="<?php echo esc_attr($data['filter_args']['name']) ?>" />
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('Blood Group') ?>
				<select name="blood_group"  class="form-control">
					<option value="all"><?php _e('All') ?></option>
					<?php foreach (Donor::$blood_groups as $blood_group) : ?>
					<option<?php html_atts(array( 'selected' => ($blood_group === $data['filter_args']['blood_group']) )) ?>><?php echo $blood_group ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('City') ?>
				<select name="city_id"  class="form-control">
					<?php foreach (Cites::fetchAll() as $city) : ?>
					<option<?php html_atts(array( 'value' => $city->getID(), 'selected' => ($city->getID() == $data['filter_args']['city_id']) )) ?>><?php $city->display('city_name') ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('District') ?>
				<select name="distr_id"  class="form-control">
					<option value="all"><?php _e('All') ?></option>
					<?php foreach (Districts::fetchAll() as $distr) : ?>
					<option<?php html_atts(array( 'value' => $distr->getID(), 'selected' => ($distr->getID() == $data['filter_args']['distr_id']) )) ?>><?php $distr->display('distr_name') ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-default"><?php _e('Search') ?></button>
		</div>

	</form>

	<table id="table-donors" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('Gender') ?></th>
				<th><?php _e('Age') ?></th>
				<th><?php _e('Blood Group') ?></th>
				<th><?php _e('City/District') ?></th>
				<th><?php _e('Phone Number') ?></th>
				<?php if ($can_manage) : ?>
				<th><?php _e('Actions') ?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody>

			<?php foreach (Donors::fetchByArgs($data['filter_args']) as $donor) : ?>

				<tr>
					<td><?php $donor->display('donor_id') ?></td>
					<td><?php $donor->display('donor_name') ?></td>
					<td><?php echo $donor->getGenderLabel() ?></td>
					<td><?php echo $donor->getAge() ?></td>
					<td><?php $donor->display('donor_blood_group') ?></td>
					<td><?php printf('%s/%s', $donor->getCityName(), $donor->getDistrictName()) ?></td>
					<td><?php $donor->display('donor_phone') ?></td>
					<?php if ($can_manage) : ?>
					<td>
						<?php if ($can_edit) : ?>
						<a href="<?php echo getSiteURL(array( 'page' => 'edit-donor', 'id' => $donor->get('donor_id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
						<?php endif; ?>
						<?php if ($can_delete) : ?>
						<a href="<?php echo getSiteURL(array( 'page' => 'manage-donors', 'action' => 'delete_donor', 'id' => $donor->get('donor_id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
						<?php endif; ?>
						<?php if ($can_approve && $donor->isPending()) : ?>
						<a href="<?php echo getSiteURL(array( 'page' => 'manage-donors', 'action' => 'approve_donor', 'id' => $donor->get('donor_id') )) ?>" class="approve-link"><i class="fa fa-check"></i></a>
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
