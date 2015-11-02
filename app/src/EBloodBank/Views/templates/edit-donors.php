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

    <form id="form-edit-donors" method="POST">

        <table id="table-donors" class="table table-entities table-bordered table-striped table-hover">

            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="cb-select-all" />
                    </th>
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
                        <td>
                            <input type="checkbox" name="donors[]" value="<?php $donor->display('id', 'attr') ?>" class="cb-select" />
                        </td>
                        <td>
                            <?= EBB\getEditDonorLink(['donor' => $donor, 'content' => EBB\escHTML($donor->get('name')), 'fallbackContent' => true]) ?>
                            <?php if ($donor->isPending()) : ?>
                                <span class="label label-warning"><?= EBB\escHTML(__('Pending')) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= EBB\escHTML($donor->getGenderTitle()) ?></td>
                        <td><?= EBB\escHTML($donor->getAge()) ?></td>
                        <td><?php $donor->display('blood_group') ?></td>
                        <td>
                            <?php $city = $donor->get('district')->get('city') ?>
                            <?= EBB\getEditCityLink(['city' => $city, 'content' => EBB\escHTML($city->get('name')), 'fallbackContent' => true]) ?>
                        </td>
                        <td>
                            <?php $district = $donor->get('district') ?>
                            <?= EBB\getEditDistrictLink(['district' => $district, 'content' => EBB\escHTML($district->get('name')), 'fallbackContent' => true]) ?>
                        </td>
                        <td>
                            <?= EBB\getEditDonorLink(['donor' => $donor, 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                            <?= EBB\getDeleteDonorLink(['donor' => $donor, 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                            <?= EBB\getApproveDonorLink(['donor' => $donor, 'content' => '<i class="glyphicon glyphicon-ok"></i>']) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="btn-group pull-right bulk-actions">
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/delete/donors')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i> <?= EBB\escHTML(__('Delete')) ?>
            </button>
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/approve/donors')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-ok"></i> <?= EBB\escHTML(__('Approve')) ?>
            </button>
        </div>

    </form>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditDonorsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDonorsURL(), ['page' => '%#%']),
        ])

    ?>

    <script src="<?= EBB\escURL(EBB\getSiteURl('/public/assets/js/edit-entities.js')) ?>"></script>

<?php
$view->displayView('footer');
