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
        <?php echo getDonorsLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-donors' ))) ?>
        <?php echo getAddDonorLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-donor' ))) ?>
	</div>

    <?php View::display('notices') ?>

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
				<th><?php _e('Actions') ?></th>
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
					<td>
                        <?php echo getEditDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="fa fa-pencil"></i>')) ?>
                        <?php echo getDeleteDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="fa fa-trash"></i>')) ?>
                        <?php echo getApproveDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="fa fa-check"></i>')) ?>
					</td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total' => (int) ceil($donorRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getEditDonorsURL(), array( 'page' => '%#%' )),
            'base_url' => getEditUsersURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
