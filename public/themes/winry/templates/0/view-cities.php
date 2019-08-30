<?php
/**
 * View cities page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Cities')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getEditCitiesLink(['content' => d__('winry', 'Edit'), 'atts' => ['class' => 'btn btn-primary btn-edit btn-edit-cities']], $context) ?>
            <?= EBB\getAddCityLink(['content' => d__('winry', 'Add New'), 'atts' => ['class' => 'btn btn-default btn-add btn-add-city']], $context) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-cities" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?= EBB\escHTML(d__('winry', 'Name')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('cities') as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td><?php $city->display('name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getCitiesURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
