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

View::display('header', ['title' => __('Edit Cities')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getCitiesLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-cities']]) ?>
            <?= EBB\getAddCityLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-city']]) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-cities" class="table table-bordered table-hover">

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
                <td><?php $city->display('name') ?></td>
                <td>
                    <?= EBB\getEditCityLink(['id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                    <?= EBB\getDeleteCityLink(['id' => $city->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?=

        EBB\getPagination([
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditCitiesURL(), ['page' => '%#%']),
        ])

    ?>

<?php
View::display('footer');
