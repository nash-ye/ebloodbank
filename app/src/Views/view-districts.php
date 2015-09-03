<?php
/**
 * View Districts Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

View::display('header', array( 'title' => __('Districts') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo getEditDistrictsLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-districts' ))) ?>
            <?php echo getAddDistrictLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-district' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-distrs" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('City') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('districts') as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td><?php $district->display('name') ?></td>
                <td><?php $district->get('city')->display('name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => getDistrictsURL(),
            'page_url' => addQueryArgs(getDistrictsURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
