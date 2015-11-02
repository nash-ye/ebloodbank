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

    <form id="form-edit-cities" method="POST">

        <table id="table-cities" class="table table-entities table-bordered table-striped table-hover">

            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="cb-select-all" />
                    </th>
                    <th><?= EBB\escHTML(__('Name')) ?></th>
                    <th><?= EBB\escHTML(__('Actions')) ?></th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($view->get('cities') as $city) : ?>

                <tr>
                    <td>
                        <input type="checkbox" name="cities[]" value="<?php $city->display('id', 'attr') ?>" class="cb-select" />
                    </td>
                    <td>
                        <?= EBB\getEditCityLink(['city' => $city, 'content' => EBB\escHTML($city->get('name')), 'fallbackContent' => true]) ?>
                    </td>
                    <td>
                        <?= EBB\getEditCityLink(['city' => $city, 'content' => '<i class="glyphicon glyphicon-pencil"></i>']) ?>
                        <?= EBB\getDeleteCityLink(['city' => $city, 'content' => '<i class="glyphicon glyphicon-trash"></i>']) ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="btn-group pull-right bulk-actions">
            <button type="submit" formaction="<?= EBB\escURL(EBB\getSiteURL('/delete/cities')) ?>" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i> <?= EBB\escHTML(__('Delete')) ?>
            </button>
        </div>

    </form>

    <?php

        $view->displayView('pagination', [
            'total'    => $view->get('pagination.total'),
            'current'  => $view->get('pagination.current'),
            'base_url' => EBB\getEditCitiesURL(),
            'page_url' => EBB\addQueryArgs(EBB\getEditCitiesURL(), ['page' => '%#%']),
        ])

    ?>

    <script src="<?= EBB\escURL(EBB\getSiteURl('/public/assets/js/edit-entities.js')) ?>"></script>

<?php
$view->displayView('footer');
