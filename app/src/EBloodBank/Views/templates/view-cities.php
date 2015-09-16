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

View::display('header', ['title' => __('Cities')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getEditCitiesLink(['content' => __('Edit'), 'atts' => ['class' => 'btn btn-primary btn-edit btn-edit-cities']]) ?>
            <?= EBB\getAddCityLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-default btn-add btn-add-city']]) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?= EBB\escHTML(__('Name')) ?></th>
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

    <?=

        EBB\getPagination([
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getCitiesURL(), ['page' => '%#%']),
        ])

    ?>

<?php
View::display('footer');
