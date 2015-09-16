<?php
/**
 * Manage Donors Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

View::display('header', ['title' => __('Edit Donors')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getDonorsLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-donors']]) ?>
            <?= EBB\getAddDonorLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-donor']]) ?>
        </div>
    </div>

    <?php View::display('notices') ?>

	<table id="table-donors" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?= EBB\escHTML(__('Name')) ?></th>
                <th><?= EBB\escHTML(__('Gender')) ?></th>
				<th><?= EBB\escHTML(__('Age')) ?></th>
				<th><?= EBB\escHTML(__('Blood Group')) ?></th>
                <th><?= EBB\escHTML(__('City')) ?></th>
				<th><?= EBB\escHTML(__('District')) ?></th>
				<th><?= EBB\escHTML(__('Phone Number')) ?></th>
				<th><?= EBB\escHTML(__('Actions')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('donors') as $donor) : ?>

				<tr>
					<td><?php $donor->display('id') ?></td>
					<td><?php $donor->display('name') ?></td>
					<td><?= EBB\escHTML($donor->getGenderTitle()) ?></td>
					<td><?= EBB\escHTML($donor->calculateAge()) ?></td>
					<td><?php $donor->display('blood_group') ?></td>
					<td><?php $donor->get('district')->get('city')->display('name') ?></td>
                    <td><?php $donor->get('district')->display('name') ?></td>
					<td><?= EBB\escHTML($donor->getMeta('phone')) ?></td>
					<td>
                        <?= EBB\getEditDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                        <?= EBB\getDeleteDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                        <?= EBB\getApproveDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>']) ?>
					</td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?=

        EBB\getPagination([
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditDonorsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDonorsURL(), ['page' => '%#%']),
        ])

    ?>

<?php
View::display('footer');
