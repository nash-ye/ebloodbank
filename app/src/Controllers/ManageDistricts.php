<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

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

            $em = EntityManager::getInstance();

            $distr_id = (int) $_GET['id'];
            $district = EntityManager::getDistrictReference($distr_id);

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

        if (isset($_GET['flag-deleted'])) {
            Notices::addNotice('deleted-district', __('The district permanently deleted.'), 'success');
        }

        $view = new View('manage-districts');
        $view();
    }
}
