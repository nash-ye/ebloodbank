<?php
/**
 * Edit districts page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Edit Districts')]);
?>

    <div class="btn-toolbar">
        <div class="btn-group" role="group">
            <?= EBB\getDistrictsLink(['content' => d__('winry', 'View'), 'atts' => ['class' => 'btn btn-default btn-view btn-view-districts']], $context) ?>
            <?= EBB\getAddDistrictLink(['content' => d__('winry', 'Add New'), 'atts' => ['class' => 'btn btn-primary btn-add btn-add-district']], $context) ?>
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
                    <th><?= EBB\escHTML(d__('winry', 'Name')) ?></th>
                    <th><?= EBB\escHTML(d__('winry', 'City')) ?></th>
                    <th><?= EBB\escHTML(d__('winry', 'Actions')) ?></th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($view->get('districts') as $district) : ?>

                <tr>
                    <td>
                        <input type="checkbox" name="districts[]" value="<?php $district->display('id', 'attr') ?>" class="cb-select" />
                    </td>
                    <td>
                        <?= EBB\getEditDistrictLink(['district' => $district, 'content' => EBB\escHTML($district->get('name')), 'fallbackContent' => true], $context) ?>
                    <td>
                        <?php $city = $district->get('city') ?>
                        <?= EBB\getEditCityLink(['city' => $city, 'content' => EBB\escHTML($city->get('name')), 'fallbackContent' => true], $context) ?>
                    </td>
                    <td>
                        <?= EBB\getEditDistrictLink(['district' => $district, 'content' => '<i class="glyphicon glyphicon-pencil"></i>'], $context) ?>
                        <?= EBB\getDeleteDistrictLink(['district' => $district, 'content' => '<i class="glyphicon glyphicon-trash"></i>'], $context) ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="btn-group pull-right bulk-actions">
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/delete/districts')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i> <?= EBB\escHTML(d__('winry', 'Delete')) ?>
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

    <script src="<?= EBB\escURL(EBB\getThemeURL('/assets/js/edit-entities.js')) ?>"></script>

<?php
$view->displayView('footer');
