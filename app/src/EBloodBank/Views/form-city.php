<?php
/**
 * New\Edit City Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Models\City;

if (! $this->isExists('city')) {
    $city = new City();
}
?>

<?php View::display('notices') ?>

<form id="form-city" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="city_name"><?php __e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="city_name" id="city_name" class="form-control" value="<?php $city->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php __e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_city" />

</form>
