<?php
/**
 * Edit districts page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Edit Districts')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getDistrictsLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-districts']]) ?>
            <?= EBB\getAddDistrictLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-district']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-districts" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<tr>
				<th>#</th>
                <th><?= EBB\escHTML(__('Name')) ?></th>
				<th><?= EBB\escHTML(__('City')) ?></th>
				<th><?= EBB\escHTML(__('Actions')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('districts') as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td>
                    <?= EBB\getEditDistrictLink(['id' => $district->get('id'), 'content' => EBB\escHTML($district->get('name'))]) ?>
                <td>
                    <?php $city = $district->get('city') ?>
                    <?= EBB\getEditCityLink(['id' => $city->get('id'), 'content' => EBB\escHTML($city->get('name'))]) ?>
                </td>
                <td>
                    <?= EBB\getEditDistrictLink(['id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                    <?= EBB\getDeleteDistrictLink(['id' => $district->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditDistrictsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDistrictsURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
