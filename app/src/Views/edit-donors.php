<?php
/**
 * Manage Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Options;

View::display('header', array( 'title' => __('Edit Donors') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getDonorsLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-donors' ))) ?>
            <?php echo getAddDonorLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-donor' ))) ?>
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
				<th><?php _e('Actions') ?></th>
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
					<td>
                        <?php echo getEditDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                        <?php echo getDeleteDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                        <?php echo getApproveDonorLink(array('id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>')) ?>
					</td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getEditDonorsURL(),
            'page_url' => addQueryArgs(getEditDonorsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
