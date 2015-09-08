<?php
/**
 * Manage Cities Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', array( 'title' => __('Edit Cities') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getCitiesLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-cities' ))) ?>
            <?php echo EBB\getAddCityLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-city' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php __e('Name') ?></th>
				<th><?php __e('Actions') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('cities') as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td><?php $city->display('name') ?></td>
                <td>
                    <?php echo EBB\getEditCityLink(array('id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                    <?php echo EBB\getDeleteCityLink(array('id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getEditCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditCitiesURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
