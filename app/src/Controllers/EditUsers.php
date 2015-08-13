<?php
/**
 * Edit Users Controller
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
class EditUsers extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete_user':
                $this->doDeleteAction();
                break;
            case 'approve_user':
                $this->doApproveAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-approved')) {
            $approved = (int) filter_input(INPUT_GET, 'flag-approved', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('approved', sprintf(_n('%s user approved.', '%s users approved.', $approved), $approved), 'success');
        }
        if (filter_has_var(INPUT_GET, 'flag-deleted')) {
            $deleted = (int) filter_input(INPUT_GET, 'flag-deleted', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('deleted', sprintf(_n('%s user permanently deleted.', '%s users permanently deleted.', $deleted), $deleted), 'success');
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doDeleteAction()
    {
        if (isCurrentUserCan('delete_user')) {

            $userID = (int) $_GET['id'];

            if (! empty($userID) && $userID != getCurrentUserID()) {

                $user = EntityManager::getUserReference($userID);

                $em = EntityManager::getInstance();
                $em->remove($user);
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditUsersURL(),
                        array('flag-deleted' => 1)
                    )
                );

            }

        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doApproveAction()
    {
        if (isCurrentUserCan('approve_user')) {

            $userID = (int) $_GET['id'];
            $user = EntityManager::getUserReference($userID);

            if (! empty($user) && $user->isPending()) {

                $user->set('status', 'activated');

                $em = EntityManager::getInstance();
                $em->flush();

                redirect(
                    addQueryArgs(
                        getEditUsersURL(),
                        array('flag-approved' => 1)
                    )
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
        if (isCurrentUserCan('edit_users')) {
            $this->doActions();
            $this->addNotices();
            $view = View::instance('edit-users');
            $view->set('page', filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
        } else {
            $view = View::instance('error-401');
        }
        $view();
    }
}
