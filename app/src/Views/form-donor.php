<?php
/**
 * New\Edit Donor Form
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Models\Donor;
use eBloodBank\Models\Donors;
use eBloodBank\Models\Cites;
use eBloodBank\Models\Districts;

if (! isset($data['id'])) {
	$donor = new Donor();
} else {
	$donor = Donors::fetchByID($data['id']);
}
?>
<form id="form-donor" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_name"><?php _e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="donor_name" id="donor_name" class="form-control" value="<?php $donor->display('donor_name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_gender"><?php _e('Gender') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_gender" id="donor_gender" class="form-control">
				<?php foreach (Donor::$genders as $key => $label) { ?>
				<option<?php html_atts(array( 'value' => $key, 'selected' => ($key === $donor->get('donor_gender')) )) ?>><?php echo $label ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_weight"><?php _e('Weight') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="number" name="donor_weight" id="donor_weight" class="form-control" value="<?php $donor->display('donor_weight', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_birthdate"><?php _e('Birthdate') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="date" name="donor_birthdate" id="donor_birthdate" class="form-control" value="<?php $donor->display('donor_birthdate', 'attr') ?>" placeholder="YYYY-MM-DD" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_blood_group"><?php _e('Blood Group') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_blood_group" id="donor_blood_group" class="form-control" required>
				<?php foreach (Donor::$blood_groups as $blood_group) : ?>
				<option<?php html_atts(array( 'selected' => ($blood_group === $donor->get('donor_blood_group')) )) ?>><?php echo $blood_group ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_phone"><?php _e('Phone Number') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="phone" name="donor_phone" id="donor_phone" class="form-control" value="<?php $donor->display('donor_phone', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_email"><?php _e('Email Address') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="email" name="donor_email" id="donor_email" value="<?php $donor->display('donor_email', 'attr') ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_distr_id"><?php _e('City/District') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_distr_id" id="donor_distr_id" class="form-control" required>

				<?php foreach (Cites::fetchAll() as $city) { ?>
					<optgroup label="<?php $city->display('city_name', 'attr') ?>">
						<?php foreach (Districts::fetchByCityID($city->get('city_id')) as $distr) { ?>
							<option<?php html_atts(array( 'value' => $distr->get('distr_id'), 'selected' => ($distr->get('distr_id') == $donor->get('donor_distr_id')) )) ?>><?php $distr->display('distr_name') ?></option>
						<?php } ?>
					</optgroup>
				<?php } ?>

			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_address"><?php _e('Address') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="donor_address" id="donor_address" class="form-control" value="<?php $donor->display('donor_address', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php _e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_donor" />

</form>
