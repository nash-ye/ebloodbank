<?php
/**
 * View Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Options;

View::display('header', array( 'title' => __('Donors') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getEditDonorsLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-donors' ))) ?>
            <?php echo getAddDonorLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-donor' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

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

            <?php foreach ($this->get('donors') as $donor) : ?>

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
					<td><?php echo escHTML($donor->getMeta('phone')) ?></td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getDonorsURL(),
            'page_url' => addQueryArgs(getDonorsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
