<?php
/**
 * New\Edit Donor Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Options;
use EBloodBank\Models\Donor;

if (! $this->isExists('donor')) {
    $donor = new Donor();
}

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
?>

<?php View::display('notices') ?>

<form id="form-donor" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_name"><?php __e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="donor_name" id="donor_name" class="form-control" value="<?php $donor->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_gender"><?php __e('Gender') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_gender" id="donor_gender" class="form-control" required>
				<?php foreach (Options::getOption('genders') as $key => $label) : ?>
				<option<?php echo EBB\toAttributes(array( 'value' => $key, 'selected' => ($key === $donor->get('gender')) )) ?>><?php echo EBB\escHTML($label) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_birthdate"><?php __e('Birthdate') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="date" name="donor_birthdate" id="donor_birthdate" class="form-control" value="<?php $donor->display('birthdate', 'attr') ?>" placeholder="YYYY-MM-DD" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_blood_group"><?php __e('Blood Group') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_blood_group" id="donor_blood_group" class="form-control" required>
				<?php foreach (Donor::getBloodGroups() as $bloodGroup) : ?>
                <option<?php echo EBB\toAttributes(array( 'selected' => ($bloodGroup === $donor->get('blood_group')) )) ?>><?php echo EBB\escHTML($bloodGroup) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_district_id"><?php __e('City, District') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_district_id" id="donor_district_id" class="form-control" required>
				<?php foreach ($cityRepository->findAll() as $city) : ?>
					<optgroup label="<?php $city->display('name', 'attr') ?>">
                        <?php foreach ($city->get('districts') as $district) : ?>
                        <option<?php echo EBB\toAttributes(array( 'value' => $district->get('id'), 'selected' => ($district === $donor->get('district')) )) ?>><?php $district->display('name') ?></option>
                        <?php endforeach; ?>
					</optgroup>
                <?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_weight"><?php __e('Weight') ?></label>
		</div>
		<div class="col-sm-4">
            <input type="number" name="donor_weight" id="donor_weight" class="form-control" value="<?php echo EBB\escAttr($donor->getMeta('weight')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_email"><?php __e('E-mail') ?></label>
		</div>
		<div class="col-sm-4">
            <input type="email" name="donor_email" id="donor_email" class="form-control" value="<?php echo EBB\escAttr($donor->getMeta('email')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_phone"><?php __e('Phone') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="phone" name="donor_phone" id="donor_phone" class="form-control" value="<?php echo EBB\escAttr($donor->getMeta('phone')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_address"><?php __e('Address') ?></label>
		</div>
		<div class="col-sm-4">
            <input type="text" name="donor_address" id="donor_address" class="form-control" value="<?php echo EBB\escAttr($donor->getMeta('address')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php __e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_donor" />

</form>
