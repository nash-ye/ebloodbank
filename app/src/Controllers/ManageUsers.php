<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\SessionManage;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class ManageUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_GET['action']) && 'delete_user' === $_GET['action']) {
            if (isCurrentUserCan('delete_user')) {
                $user_id = (int) $_GET['id'];

                if (! empty($user_id) && $user_id !== SessionManage::getCurrentUserID()) {

                    $em = EntityManager::getInstance();
                    $em->remove($em->getUserReference($user_id));
                    $em->flush();

                    redirect(getPageURL('manage-users', array( 'flag-deleted' => true )));

                }
            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        $view = new View('manage-users');
        $view();
    }
}
