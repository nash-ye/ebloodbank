<?php
/**
 * Manage Cities Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Options;
use EBloodBank\Kernal\Notices;

$limit = Options::getOption('entities_per_page');
$pageNumber = max((int) $this->get('page'), 1);
$offset = ($pageNumber - 1) * $limit;

$cityRepository = EntityManager::getCityRepository();
$cities = $cityRepository->findBy(array(), array(), $limit, $offset);

View::display('header', array( 'title' => __('Cities') ));
?>

	<div class="btn-block">
        <?php echo getCitiesLink(array('content' => __('View'), 'atts' => array( 'class' => 'btn btn-default btn-view btn-view-cities' ))) ?>
        <?php echo getAddCityLink(array('content' => __('Add New'), 'atts' => array( 'class' => 'btn btn-primary btn-add btn-add-city' ))) ?>
	</div>

    <?php Notices::displayNotices() ?>

	<table id="table-cities" class="table table-bordered table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th><?php _e('Name') ?></th>
				<th><?php _e('Actions') ?></th>
			</tr>
		</thead>

		<tbody>

            <?php foreach ($cities as $city) : ?>

            <tr>
                <td><?php $city->display('id') ?></td>
                <td><?php $city->display('name') ?></td>
                <td>
                    <?php echo getEditCityLink(array('id' => $city->get('id'), 'content' => '<i class="fa fa-pencil"></i>')) ?>
                    <?php echo getDeleteCityLink(array('id' => $city->get('id'), 'content' => '<i class="fa fa-trash"></i>')) ?>
                </td>
            </tr>

            <?php endforeach; ?>

		</tbody>

	</table>

    <?php

        paginationLinks(array(
            'total' => (int) ceil($cityRepository->countAll() / $limit),
            'page_url' => addQueryArgs(getEditCitiesURL(), array( 'page' => '%#%' )),
            'base_url' => getEditCitiesURL(),
            'current' => $pageNumber,
        ))

    ?>

<?php
View::display('footer');
