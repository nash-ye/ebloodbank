<?php
/**
 * New\Edit District Form
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Models\District;

if (! $this->isExists('district')) {
    $district = new District();
}

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
?>

<?php View::display('notices') ?>

<form id="form-district" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_name"><?php _e('Name') ?></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="district_name" id="district_name" class="form-control" value="<?php $district->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_city_id"><?php _e('City') ?></label>
		</div>
		<div class="col-sm-4">
			<select name="district_city_id" id="district_city_id" class="form-control" required>
				<?php foreach ($cityRepository->findAll() as $city) : ?>
                <option<?php echo toAttributes(array( 'value' => $city->get('id'), 'selected' => ($city === $district->get('city')) )) ?>><?php $city->display('name') ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?php _e('Submit') ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_district" />

</form>
