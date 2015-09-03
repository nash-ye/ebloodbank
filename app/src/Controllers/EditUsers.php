<?php
/**
 * Edit Users Controller
 *
 * @package EBloodBank
 * @subpackage Controllers
 * @since 1.0
 */
namespace EBloodBank\Controllers;

use EBloodBank\Notices;
use EBloodBank\Views\View;

/**
 * @since 1.0
 */
class EditUsers extends ViewUsers
{
    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (isCurrentUserCan('edit_users')) {
            $this->doActions();
            $this->addNotices();
            $view = View::forge('edit-users', array(
                'users' => $this->getQueriedUsers(),
                'pagination.total' => $this->getPagesTotal(),
                'pagination.current' => $this->getCurrentPage(),
            ));
        } else {
            $view = View::forge('error-403');
        }
        $view();
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function doActions()
    {
        switch (filter_input(INPUT_GET, 'action')) {
            case 'delete':
                $this->doDeleteAction();
                break;
            case 'activate':
                $this->doActivateAction();
                break;
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function addNotices()
    {
        if (filter_has_var(INPUT_GET, 'flag-activated')) {
            $activated = (int) filter_input(INPUT_GET, 'flag-activated', FILTER_SANITIZE_NUMBER_INT);
            Notices::addNotice('activated', sprintf(_n('%s user activated.', '%s users activated.', $activated), $activated), 'success');
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

            $userID = filter_input(INPUT_GET, 'id');

            if (! isValidID($userID)) {
                return;
            }

            if ($userID == getCurrentUserID()) {
                return;
            }

            $em = main()->getEntityManager();
            $user = $em->getReference('Entities:User', $userID);
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

    /**
     * @return void
     * @since 1.0
     */
    protected function doActivateAction()
    {
        if (isCurrentUserCan('activate_user')) {

            $userID = filter_input(INPUT_GET, 'id');

            if (! isValidID($userID)) {
                return;
            }

            $em = main()->getEntityManager();
            $user = $em->getReference('Entities:User', $userID);

            if (! $user->isPending()) {
                return;
            }

            $user->set('status', 'activated');

            $em->flush();

            redirect(
                addQueryArgs(
                    getEditUsersURL(),
                    array('flag-activated' => 1)
                )
            );

        }
    }
}
