<?php
/**
 * Manage Districts Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

$em = main()->getEntityManager();
$donorRepository = $em->getRepository('Entities:Donor');

View::display('header', array( 'title' => __('Edit Districts') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getDistrictsLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-districts' ))) ?>
            <?php echo EBB\getAddDistrictLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-district' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-districts" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php __e('Name') ?></th>
				<th><?php __e('City') ?></th>
				<th><?php __e('Actions') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('districts') as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td><?php $district->display('name') ?></td>
                <td><?php $district->get('city')->display('name') ?></td>
                <td>
                    <?php echo EBB\getEditDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                    <?php echo EBB\getDeleteDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getEditDistrictsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDistrictsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
