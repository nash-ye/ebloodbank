<?php
/**
 * Manage Users Controller
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
class ManageUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function action_delete()
    {
        if (isCurrentUserCan('delete_user')) {

            $userID = (int) $_GET['id'];

            if (! empty($userID) && $userID != getCurrentUserID()) {

                $user = EntityManager::getUserReference($userID);

                $em = EntityManager::getInstance();
                $em->remove($user);
                $em->flush();

                redirect(
                    getPageURL('manage-users', array(
                        'flag-deleted' => true
                    ))
                );

            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function action_approve()
    {
        if (isCurrentUserCan('approve_user')) {

            $userID = (int) $_GET['id'];
            $user = EntityManager::getUserReference($userID);

            if (! empty($user) && $user->isPending()) {

                $user->set('status', 'activated');

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    getPageURL('manage-users', array(
                        'flag-approved' => true
                    ))
                );

            }
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
                case 'delete_user':
                    $this->action_delete();
                    break;
                case 'approve_user':
                    $this->action_approve();
                    break;
            }
        }

        if (isCurrentUserCan('manage_users')) {
            if (isset($_GET['flag-deleted']) && $_GET['flag-deleted']) {
                Notices::addNotice('user_deleted', __('The user permanently deleted.'), 'success');
            }
            $view = new View('manage-users');
        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
