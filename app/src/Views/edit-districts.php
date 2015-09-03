<?php
/**
 * Manage Districts Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Edit Districts') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getDistrictsLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-districts' ))) ?>
            <?php echo getAddDistrictLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-district' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-districts" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('City') ?></th>
				<th><?php _e('Actions') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('districts') as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td><?php $district->display('name') ?></td>
                <td>
                    <?php $district->get('city')->display('name') ?>
                </td>
                <td>
                    <?php echo getEditDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>')) ?>
                    <?php echo getDeleteDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>')) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getEditDistrictsURL(),
            'page_url' => addQueryArgs(getEditDistrictsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
