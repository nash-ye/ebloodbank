<?php
/**
 * Edit donors page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Edit Donors')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getDonorsLink(['content' => __('View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-donors']]) ?>
            <?= EBB\getAddDonorLink(['content' => __('Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-donor']]) ?>
        </div>
    </div>

    <?php $view->displayView('notices') ?>

    <?php

        $view->displayView('form-donors-filter', [
            'criteria' => $this->get('filter.criteria')
        ])

    ?>

	<table id="table-donors" class="table table-entities table-bordered table-striped table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?= EBB\escHTML(__('Name')) ?></th>
                <th><?= EBB\escHTML(__('Gender')) ?></th>
				<th><?= EBB\escHTML(__('Age')) ?></th>
				<th><?= EBB\escHTML(__('Blood Group')) ?></th>
                <th><?= EBB\escHTML(__('City')) ?></th>
				<th><?= EBB\escHTML(__('District')) ?></th>
				<th><?= EBB\escHTML(__('Actions')) ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($view->get('donors') as $donor) : ?>

				<tr>
					<td><?php $donor->display('id') ?></td>
					<td>
                        <?php $donor->display('name') ?>
                        <?php if ($donor->isPending()) : ?>
                            <span class="label label-warning"><?= EBB\escHTML(__('Pending')) ?></span>
                        <?php endif; ?>
                    </td>
					<td><?= EBB\escHTML($donor->getGenderTitle()) ?></td>
					<td><?= EBB\escHTML($donor->getAge()) ?></td>
					<td><?php $donor->display('blood_group') ?></td>
					<td><?php $donor->get('district')->get('city')->display('name') ?></td>
                    <td><?php $donor->get('district')->display('name') ?></td>
					<td>
                        <?= EBB\getEditDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                        <?= EBB\getDeleteDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                        <?= EBB\getApproveDonorLink(['id' => $donor->get('id'), 'content' => '<i class="glyphicon glyphicon-ok"></i>']) ?>
					</td>
				</tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditDonorsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDonorsURL(), ['page' => '%#%']),
        ])

    ?>

<?php
$view->displayView('footer');
