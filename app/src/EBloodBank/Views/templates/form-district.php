<?php
/**
 * New\Edit district form template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$em = main()->getEntityManager();
$cityRepository = $em->getRepository('Entities:City');
?>

<?php $view->displayView('notices') ?>

<form id="form-district" class="form-horizontal" method="POST">

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_name"><?= EBB\escHTML(__('Name')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<input type="text" name="district_name" id="district_name" class="form-control" value="<?php $district->display('name', 'attr') ?>" required />
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2">
			<label for="district_city_id"><?= EBB\escHTML(__('City')) ?> <span class="form-required">*</span></label>
		</div>
		<div class="col-sm-4">
			<select name="district_city_id" id="district_city_id" class="form-control" required>
				<?php foreach ($cityRepository->findAll() as $city) : ?>
                <option<?= EBB\toAttributes(['value' => $city->get('id'), 'selected' => ($city === $district->get('city'))]) ?>><?php $city->display('name') ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			<button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Submit')) ?></button>
		</div>
	</div>

	<input type="hidden" name="action" value="submit_district" />
    <?= EBB\getTokenField(['name' => 'token']) ?>

</form>
