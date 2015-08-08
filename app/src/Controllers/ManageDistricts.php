<?php
/**
 * Manage Districts Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\Notices;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class ManageDistricts extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function action_delete()
    {
        if (isCurrentUserCan('delete_district')) {

            $districtID = (int) $_GET['id'];
            $district = EntityManager::getDistrictReference($districtID);

            $em = EntityManager::getInstance();
            $em->remove($district);
            $em->flush();

            redirect(
                getPageURL('manage-districts', array(
                    'flag-deleted' => true
                ))
            );

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (! empty($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete_district':
                    $this->action_delete();
                    break;
            }
        }

        if (isCurrentUserCan('manage_districts')) {
            if (isset($_GET['flag-deleted']) && $_GET['flag-deleted']) {
                Notices::addNotice('district_deleted', __('The district permanently deleted.'), 'success');
            }
            $view = new View('manage-districts');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
