<?php
/**
 * New\Edit city form template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
?>

<?php $view->displayView('notices') ?>

<form id="form-city" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="city_name"><?= EBB\escHTML(d__('winry', 'Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="city_name" id="city_name" class="form-control" value="<?php $city->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(d__('winry', 'Submit')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_city" />
    <?= EBB\getTokenField(['name' => 'token']) ?>

</form>
