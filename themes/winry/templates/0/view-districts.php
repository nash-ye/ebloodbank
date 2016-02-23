<?php
/**
 * View districts page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Districts')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getEditDistrictsLink(['content' => d__('winry', 'Edit'), 'atts' => ['class' => 'btn btn-primary btn-edit btn-edit-districts']]) ?>
            <?= EBB\getAddDistrictLink(['content' => d__('winry', 'Add New'), 'atts' => ['class' => 'btn btn-default btn-add btn-add-district']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

	<table id="table-distrs" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?= EBB\escHTML(d__('winry', 'Name')) ?></th>
				<th><?= EBB\escHTML(d__('winry', 'City')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('districts') as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td><?php $district->display('name') ?></td>
                <td><?php $district->get('city')->display('name') ?></td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getDistrictsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getDistrictsURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
