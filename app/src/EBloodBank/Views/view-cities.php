<?php
/**
 * View Cities Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', array( 'title' => __('Cities') ));
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?php echo EBB\getEditCitiesLink(array('content' => __('Edit'), 'atts' => array( 'class' => 'btn btn-primary btn-edit btn-edit-cities' ))) ?>
            <?php echo EBB\getAddCityLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-default btn-add btn-add-city' ))) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php __e('Name') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($this->get('cities') as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td><?php $city->display('name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo EBB\getPagination(array(
            'total'    => $this->get('pagination.total'),
            'current'  => $this->get('pagination.current'),
            'base_url' => EBB\getCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getCitiesURL(), array( 'page' => '%#%' )),
        ))

    ?>

<?php
View::display('footer');
