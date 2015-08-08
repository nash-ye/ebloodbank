<?php
/**
 * Manage Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;
use EBloodBank\Kernal\Notices;

$donorsCriteria = array_merge( array(
    'blood_group' => 'any',
    'district'    => -1,
    'city_id'     => -1,
), (array) $this->get('donorsCriteria') );

$can_edit    = isCurrentUserCan('edit_donor');
$can_delete  = isCurrentUserCan('delete_donor');
$can_manage  = isCurrentUserCan('manage_donors');
$can_approve = isCurrentUserCan('approve_donor');

$donorRepository    = EntityManager::getDonorRepository();
$cityRepository     = EntityManager::getCityRepository();
$districtRepository = EntityManager::getDistrictRepository();

$donors = $donorRepository->findBy($donorsCriteria);

$header = new View('header', array( 'title' => __('Donors') ));
$header();
?>

	<div class="btn-block">

        <?php if (isCurrentUserCan('view_donors')) : ?>
		<a href="<?php echo getPageURL('view-donors') ?>" class="btn btn-default btn-manage"><?php _e('View') ?></a>
        <?php endif; ?>

        <?php if (isCurrentUserCan('add_donor')) : ?>
		<a href="<?php echo getPageURL('new-donor') ?>" class="btn btn-primary btn-add-new"><?php _e('Add New') ?></a>
        <?php endif; ?>

	</div>

    <?php Notices::displayNotices() ?>

	<form class="form-inline" action="<?php echo getPageURL('manage-donors') ?>" method="POST">

		<div class="form-group">
			<label>
				<?php _e('Name') ?>
				<input type="text" name="name"  class="form-control" value="<?php echo esc_attr('') ?>" />
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('Blood Group') ?>
				<select name="blood_group"  class="form-control">
					<option value="all"><?php _e('All') ?></option>
                    <?php foreach (Options::getOption('blood_groups') as $blood_group) : ?>
					<option<?php html_atts(array( 'selected' => ($blood_group === $donorsCriteria['blood_group']) )) ?>><?php echo $blood_group ?></option>
                    <?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('City') ?>
				<select name="city_id"  class="form-control">
                    <?php foreach ($cityRepository->findAll() as $city) : ?>
					<option<?php html_atts(array( 'value' => $city->get('id'), 'selected' => ($city->get('id') == $donorsCriteria['city_id']) )) ?>><?php $city->display('name') ?></option>
                    <?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<label>
				<?php _e('District') ?>
				<select name="distr_id"  class="form-control">
					<option value="all"><?php _e('All') ?></option>
                    <?php foreach ($districtRepository->findAll() as $distr) : ?>
					<option<?php html_atts(array( 'value' => $distr->get('id'), 'selected' => ($distr->get('id') == $donorsCriteria['district']) )) ?>><?php $distr->display('name') ?></option>
                    <?php endforeach; ?>
				</select>
			</label>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-default"><?php _e('Search') ?></button>
		</div>

	</form>

	<table id="table-donors" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('Gender') ?></th>
				<th><?php _e('Age') ?></th>
				<th><?php _e('Blood Group') ?></th>
				<th><?php _e('City, District') ?></th>
				<th><?php _e('Phone Number') ?></th>
				<?php if ($can_manage) : ?>
				<th><?php _e('Actions') ?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($donors as $donor) : ?>

				<tr>
					<td><?php $donor->display('id') ?></td>
					<td><?php $donor->display('name') ?></td>
					<td>
                        <?php
                            $donorGender = $donor->get('gender');
                            $genders = Options::getOption('genders');
                            if (isset($genders[$donorGender])) {
                                echo $genders[$donorGender];
                            }
                        ?>
                    </td>
					<td><?php echo $donor->calculateAge() ?></td>
					<td><?php $donor->display('blood_group') ?></td>
					<td>
                        <?php
                            $district = $donor->get('district');
                            $city = $district->get('city');
                            printf('%s, %s', $city->get('name'), $district->get('name'));
                        ?>
                    </td>
					<td><?php $donor->display('phone') ?></td>
                    <?php if ($can_manage) : ?>
					<td>
                        <?php if ($can_edit) : ?>
						<a href="<?php echo getPageURL('edit-donor', array( 'id' => $donor->get('id') )) ?>" class="edit-link"><i class="fa fa-pencil"></i></a>
                        <?php endif; ?>
                        <?php if ($can_delete) : ?>
						<a href="<?php echo getPageURL('manage-donors', array( 'action' => 'delete_donor', 'id' => $donor->get('id') )) ?>" class="delete-link"><i class="fa fa-trash"></i></a>
                        <?php endif; ?>
                        <?php if ($can_approve && $donor->isPending()) : ?>
						<a href="<?php echo getPageURL('manage-donors', array( 'action' => 'approve_donor', 'id' => $donor->get('id') )) ?>" class="approve-link"><i class="fa fa-check"></i></a>
                        <?php endif; ?>
					</td>
                    <?php endif; ?>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

<?php
$footer = new View('footer');
$footer();
