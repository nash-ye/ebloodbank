<?php
/**
 * View Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Options;

View::display('header', array( 'title' => __('Donors') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getEditDonorsLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-donors' ))) ?>
            <?php echo EBB\getAddDonorLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-donor' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-donors" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php __e('Name') ?></th>
				<th><?php __e('Gender') ?></th>
				<th><?php __e('Age') ?></th>
				<th><?php __e('Blood Group') ?></th>
				<th><?php __e('City, District') ?></th>
				<th><?php __e('Phone Number') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('donors') as $donor) : ?>

				<tr>
					<td><?php $donor->display('id') ?></td>
					<td><?php $donor->display('name') ?></td>
					<td><?php echo EBB\escHTML($donor->getGenderTitle()) ?></td>
					<td><?php echo EBB\escHTML($donor->calculateAge()) ?></td>
					<td><?php $donor->display('blood_group') ?></td>
					<td>
                        <?php
                            $district = $donor->get('district');
                            $city = $district->get('city');
                            printf('%s, %s', $city->get('name'), $district->get('name'));
                        ?>
                    </td>
					<td><?php echo EBB\escHTML($donor->getMeta('phone')) ?></td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getDonorsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getDonorsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
