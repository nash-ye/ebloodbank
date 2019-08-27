<?php
/**
 * New\Edit district form template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

?>

<?php $view->displayView('notices') ?>

<form id="form-district" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_name"><?= EBB\escHTML(d__('winry', 'Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="district_name" id="district_name" class="form-control" value="<?php $district->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_city_id"><?= EBB\escHTML(d__('winry', 'City')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
            <?=
                EBB\getCitiesDropdown([
                    'id'       => 'district_city_id',
                    'name'     => 'district_city_id',
                    'selected' => $district->get('city'),
                    'atts'     => ['class' => 'form-control', 'required' => true],
                ])
            ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(d__('winry', 'Submit')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_district" />
    <?= EBB\getTokenField(['name' => 'token']) ?>

</form>
