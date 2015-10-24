<?php
/**
 * Edit cities page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Edit Cities')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getCitiesLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-cities']]) ?>
            <?= EBB\getAddCityLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-city']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-cities" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<tr>
				<th>#</th>
                <th><?= EBB\escHTML(__('Name')) ?></th>
                <th><?= EBB\escHTML(__('Actions')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('cities') as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td>
                    <?= EBB\getEditCityLink(['id' => $city->get('id'), 'content' => EBB\escHTML($city->get('name'))]) ?>
                </td>
                <td>
                    <?= EBB\getEditCityLink(['id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                    <?= EBB\getDeleteCityLink(['id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditCitiesURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
