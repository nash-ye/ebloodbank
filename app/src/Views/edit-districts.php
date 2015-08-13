<?php
/**
 * Manage Districts Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;

$limit = Options::getOption('entities_per_page');
$pageNumber = max((int) $this->get('page'), 1);
$offset = ($pageNumber - 1) * $limit;

$districtRepository = EntityManager::getDistrictRepository();
$districts = $districtRepository->findBy(array(), array(), $limit, $offset);

View::display('header', array( 'title' => __('Districts') ));
?>

	<div class="btn-block">
        <?php echo getDistrictsLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-districts' ))) ?>
        <?php echo getAddDistrictLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-district' ))) ?>
	</div>

    <?php View::display('notices') ?>

	<table id="table-districts" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('City') ?></th>
				<th><?php _e('Actions') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($districts as $district) : ?>

            <tr>
                <td><?php $district->display('id') ?></td>
                <td><?php $district->display('name') ?></td>
                <td>
                    <?php $district->get('city')->display('name') ?>
                </td>
                <td>
                    <?php echo getEditDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="fa fa-pencil"></i>')) ?>
                    <?php echo getDeleteDistrictLink(array('id' => $district->get('id'), 'content' => '<i class="fa fa-trash"></i>')) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        echo getPagination(array(
            'total' => (int) ceil($districtRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getEditDistrictsURL(), array( 'page' => '%#%' )),
            'base_url' => getEditDistrictsURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
