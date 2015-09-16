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
use EBloodBank\Models\Donor;

if (! $view->isExists('donor')) {
    $donor = new Donor();
}

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
?>

<?php View::display('notices') ?>

<form id="form-donor" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_name"><?= EBB\escHTML(__('Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="donor_name" id="donor_name" class="form-control" value="<?php $donor->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_gender"><?= EBB\escHTML(__('Gender')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_gender" id="donor_gender" class="form-control" required>
				<?php foreach (Donor::getGenderTitles() as $key => $label) : ?>
				<option<?= EBB\toAttributes(['value' => $key, 'selected' => ($key === $donor->get('gender'))]) ?>><?= EBB\escHTML($label) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_birthdate"><?= EBB\escHTML(__('Birthdate')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="date" name="donor_birthdate" id="donor_birthdate" class="form-control" value="<?php $donor->display('birthdate', 'attr') ?>" placeholder="YYYY-MM-DD" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_blood_group"><?= EBB\escHTML(__('Blood Group')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_blood_group" id="donor_blood_group" class="form-control" required>
				<?php foreach (Donor::getBloodGroups() as $bloodGroup) : ?>
                <option<?= EBB\toAttributes(['selected' => ($bloodGroup === $donor->get('blood_group'))]) ?>><?= EBB\escHTML($bloodGroup) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_district_id"><?= EBB\escHTML(__('City > District')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<select name="donor_district_id" id="donor_district_id" class="form-control" required>
				<?php foreach ($cityRepository->findAll() as $city) : ?>
					<optgroup label="<?php $city->display('name', 'attr') ?>">
                        <?php foreach ($city->get('districts') as $district) : ?>
                        <option<?= EBB\toAttributes(['value' => $district->get('id'), 'selected' => ($district === $donor->get('district'))]) ?>><?php $district->display('name') ?></option>
                        <?php endforeach; ?>
					</optgroup>
                <?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_weight"><?= EBB\escHTML(__('Weight')) ?></label>
		</div>
		<div class="col-sm-4">
            <input type="number" name="donor_weight" id="donor_weight" class="form-control" value="<?= EBB\escAttr($donor->getMeta('weight')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_email"><?= EBB\escHTML(__('E-mail')) ?></label>
		</div>
		<div class="col-sm-4">
            <input type="email" name="donor_email" id="donor_email" class="form-control" value="<?= EBB\escAttr($donor->getMeta('email')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_phone"><?= EBB\escHTML(__('Phone')) ?></label>
		</div>
		<div class="col-sm-4">
			<input type="phone" name="donor_phone" id="donor_phone" class="form-control" value="<?= EBB\escAttr($donor->getMeta('phone')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_address"><?= EBB\escHTML(__('Address')) ?></label>
		</div>
		<div class="col-sm-4">
            <input type="text" name="donor_address" id="donor_address" class="form-control" value="<?= EBB\escAttr($donor->getMeta('address')) ?>" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Submit')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_donor" />

</form>
