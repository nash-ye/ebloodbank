<?php
/**
 * View Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;
use EBloodBank\Kernal\Notices;

$criteria = array_merge( array(
    'blood_group' => 'any',
    'district'    => -1,
    'city_id'     => -1,
), (array) $this->get('donorsCriteria') );

$limit = Options::getOption('entities_per_page');
$pageNumber = max((int) $this->get('page'), 1);
$offset = ($pageNumber - 1) * $limit;

$donorRepository = EntityManager::getDonorRepository();
$donors = $donorRepository->findBy($criteria, array(), $limit, $offset);

View::display('header', array( 'title' => __('Donors') ));
?>

	<div class="btn-block">
        <?php echo getEditDonorsLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-donors' ))) ?>
        <?php echo getAddDonorLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-donor' ))) ?>
	</div>

    <?php Notices::displayNotices() ?>

    <?php View::display('form-search') ?>

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
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        paginationLinks(array(
            'total' => (int) ceil($donorRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getDonorsURL(), array( 'page' => '%#%' )),
            'base_url' => getDonorsURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
