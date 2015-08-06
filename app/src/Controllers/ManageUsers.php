<?php
namespace EBloodBank\Controllers;

use EBloodBank\EntityManager;
use EBloodBank\Kernal\View;
use EBloodBank\Kernal\Controller;
use EBloodBank\Kernal\Notices;

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

            $user_id = (int) $_GET['id'];

            if (! empty($user_id) && $user_id !== getCurrentUserID()) {

                $user = EntityManager::getUserReference($user_id);

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

            $user_id = (int) $_GET['id'];
            $user = EntityManager::getUserReference($user_id);

            if (! empty($user) && $user->isPending()) {

                $user->set('user_status', 'activated');

                EntityManager::getInstance()->flush();

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
        if (isCurrentUserCan('manage_users')) {

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

            if (isset($_GET['flag-deleted'])) {
                Notices::addNotice('deleted-user', __('The user permanently deleted.'), 'success');
            }

            $view = new View('manage-users');

        } else {
            $view = new View('error-401');
        }

        $view();
    }
}
