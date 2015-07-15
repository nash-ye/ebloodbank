<?php
/**
 * New\Edit District Form
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\EntityManager;
use eBloodBank\Models\District;

if (! isset($__districtID)) {
    $distr = new District();
} else {
    $distr = EntityManager::getDistrictRepository()->find($__districtID);
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
				<?php foreach (EntityManager::getCityRepository()->findAll() as $city) : ?>
                <option<?php html_atts(array( 'value' => $city->get('city_id'), 'selected' => ($city->get('city_id') == $distr->get('distr_city_id')) )) ?>><?php $city->display('city_name') ?></option>
				<?php endforeach; ?>
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
