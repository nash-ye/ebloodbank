<?php
/**
 * New\Edit District Form
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Models\Cites;
use eBloodBank\Models\District;
use eBloodBank\Models\Districts;

if (! isset($data['id'])) {
	$distr = new District();
} else {
	$distr = Districts::fetchByID($data['id']);
}
?>
<form id="form-distr" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="distr_name"><?php _e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="distr_name" id="distr_name" class="form-control" value="<?php $distr->display('distr_name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="distr_city_id"><?php _e('City') ?></label>
		</div>
		<div class="col-sm-4">
			<select id="distr_city_id" name="distr_city_id" class="form-control">
				<?php foreach (Cites::fetchAll() as $city) { ?>
					<option<?php html_atts(array( 'value' => $city->getID(), 'selected' => ($city->getID() == $distr->get('distr_city_id')) )) ?>>
						<?php $city->display('city_name') ?>
					</option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php _e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_distr" />

</form>
