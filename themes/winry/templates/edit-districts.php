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

    <form id="form-edit-districts" method="POST">

        <table id="table-districts" class="table table-entities table-bordered table-striped table-hover">

            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="cb-select-all" />
                    </th>
                    <th><?= EBB\escHTML(__('Name')) ?></th>
                    <th><?= EBB\escHTML(__('City')) ?></th>
                    <th><?= EBB\escHTML(__('Actions')) ?></th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($view->get('districts') as $district) : ?>

                <tr>
                    <td>
                        <input type="checkbox" name="districts[]" value="<?php $district->display('id', 'attr') ?>" class="cb-select" />
                    </td>
                    <td>
                        <?= EBB\getEditDistrictLink(['district' => $district, 'content' => EBB\escHTML($district->get('name')), 'fallbackContent' => true]) ?>
                    <td>
                        <?php $city = $district->get('city') ?>
                        <?= EBB\getEditCityLink(['city' => $city, 'content' => EBB\escHTML($city->get('name')), 'fallbackContent' => true]) ?>
                    </td>
                    <td>
                        <?= EBB\getEditDistrictLink(['district' => $district, 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                        <?= EBB\getDeleteDistrictLink(['district' => $district, 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="btn-group pull-right bulk-actions">
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/delete/districts')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i> <?= EBB\escHTML(__('Delete')) ?>
            </button>
        </div>

    </form>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditDistrictsURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditDistrictsURL(), ['page' => '%#%']),
        ])

    ?>

    <script src="<?= EBB\escURL(EBB\getSiteURl('/themes/winry/assets/js/edit-entities.js')) ?>"></script>

<?php
$view->displayView('footer');
