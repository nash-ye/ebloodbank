<?php
/**
 * Sign-up form template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
use EBloodBank\Models\Donor;

?>

<script>
    jQuery(document).ready(function ($) {
        var fieldsetDonor = $('#fieldset-signup-donor');
        $('#add_as_a_donor').change(function(e) {
            fieldsetDonor.toggle();
            if (fieldsetDonor.is(':hidden')) {
                $('[data-required="true"]').removeAttr('required');
            } else {
                $('[data-required="true"]').attr('required', true);
            }
        });
    });
</script>

<?php $view->displayView('notices') ?>

<form id="form-signup" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_name"><?= EBB\escHTML(__('Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="user_name" id="user_name" class="form-control" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_email"><?= EBB\escHTML(__('E-mail')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="email" name="user_email" id="user_email" class="form-control" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="user_pass"><?= EBB\escHTML(__('Password')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="password" name="user_pass_1" id="user_pass_1" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type your password')) ?>" autocomplete="off" />
			&nbsp;
			<input type="password" name="user_pass_2" id="user_pass_2" class="form-control" value="" placeholder="<?= EBB\escAttr(__('Type your password again')) ?>" autocomplete="off" />
		</div>
	</div>

    <div class="form-group">
        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="add_as_a_donor" id="add_as_a_donor" />
                    <?= EBB\escHTML(__('Add as a donor.')) ?>
                </label>
            </div>
        </div>
    </div>

    <fieldset id="fieldset-signup-donor" hidden>

        <legend><?= EBB\escHTML(__('Donor Information')) ?></legend>

        <div class="form-group">
            <div class="col-sm-2">
                <label for="donor_gender"><?= EBB\escHTML(__('Gender')) ?> <span class="form-required">*</span></label>
            </div>
            <div class="col-sm-4">
                <select name="donor_gender" id="donor_gender" class="form-control" data-required="true">
                    <?php foreach (Donor::getGenderTitles() as $key => $label) : ?>
                    <option value="<?= EBB\escAttr($key) ?>"><?= EBB\escHTML($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <label for="donor_birthdate"><?= EBB\escHTML(__('Birthdate')) ?> <span class="form-required">*</span></label>
            </div>
            <div class="col-sm-4">
                <input type="date" name="donor_birthdate" id="donor_birthdate" class="form-control" placeholder="YYYY-MM-DD" data-required="true" />
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
                        'atts'     => ['class' => 'form-control', 'data-required' => 'true'],
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <label for="donor_weight"><?= EBB\escHTML(__('Weight (kg)')) ?></label>
            </div>
            <div class="col-sm-4">
                <input type="number" name="donor_weight" id="donor_weight" class="form-control" step="0.1" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <label for="donor_phone"><?= EBB\escHTML(__('Phone')) ?></label>
            </div>
            <div class="col-sm-4">
                <input type="phone" name="donor_phone" id="donor_phone" class="form-control" />
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
                        'atts'     => ['class' => 'form-control', 'data-required' => 'true'],
                    ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <label for="donor_address"><?= EBB\escHTML(__('Address')) ?></label>
            </div>
            <div class="col-sm-4">
                <input type="text" name="donor_address" id="donor_address" class="form-control" />
            </div>
        </div>

    </fieldset>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Sign Up')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="signup" />

</form>
