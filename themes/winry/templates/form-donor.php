<?php
/**
 * New\Edit donor form template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
use EBloodBank\Options;

$donorEmailVisibility = $donor->getMeta('email_visibility');
if (empty($donorEmailVisibility) && ! $donor->isExists()) {
    $donorEmailVisibility = Options::getOption('default_donor_email_visibility');
}

$donorPhoneVisibility = $donor->getMeta('phone_visibility');
if (empty($donorPhoneVisibility) && ! $donor->isExists()) {
    $donorPhoneVisibility = Options::getOption('default_donor_phone_visibility');
}
?>

<?php $view->displayView('notices') ?>

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
				<?php foreach (EBB\getGenders() as $key => $label) : ?>
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
            <?=
                EBB\getBloodGroupsDropdown([
                    'id'       => 'donor_blood_group',
                    'name'     => 'donor_blood_group',
                    'selected' => $donor->get('blood_group'),
                    'atts'     => ['class' => 'form-control', 'required' => true],
                ])
            ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_weight"><?= EBB\escHTML(__('Weight (kg)')) ?></label>
		</div>
		<div class="col-sm-4">
            <input type="number" name="donor_weight" id="donor_weight" class="form-control" value="<?= EBB\escAttr($donor->getMeta('weight')) ?>" step="0.1" />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_email"><?= EBB\escHTML(__('E-mail')) ?></label>
		</div>
		<div class="col-sm-4">
            <input type="email" name="donor_email" id="donor_email" class="form-control" value="<?= EBB\escAttr($donor->getMeta('email')) ?>" />
			<div id="donor-email-visibilities">
				<?php foreach (EBB\getVisibilities() as $visibilityKey => $visibilityTitle) : ?>
				<label class="radio-inline">
					<input<?= EBB\toAttributes(['type' => 'radio', 'name' => 'donor_email_visibility', 'value' => $visibilityKey, 'checked' => $visibilityKey === $donorEmailVisibility]) ?>/>
					<?= EBB\escHTML($visibilityTitle) ?>
				</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_phone"><?= EBB\escHTML(__('Phone')) ?></label>
		</div>
		<div class="col-sm-4">
			<input type="phone" name="donor_phone" id="donor_phone" class="form-control" value="<?= EBB\escAttr($donor->getMeta('phone')) ?>" />
			<div id="donor-phone-visibilities">
				<?php foreach (EBB\getVisibilities() as $visibilityKey => $visibilityTitle) : ?>
				<label class="radio-inline">
					<input<?= EBB\toAttributes(['type' => 'radio', 'name' => 'donor_phone_visibility', 'value' => $visibilityKey, 'checked' => $visibilityKey === $donorPhoneVisibility]) ?>/>
					<?= EBB\escHTML($visibilityTitle) ?>
				</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="donor_district_id"><?= EBB\escHTML(__('District')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
            <?=
                EBB\getDistrictsDropdown([
                    'id'       => 'donor_district_id',
                    'name'     => 'donor_district_id',
                    'selected' => $donor->get('district'),
                    'atts'     => ['class' => 'form-control', 'required' => true],
                ])
            ?>
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
    <?= EBB\getTokenField(['name' => 'token']) ?>

</form>
