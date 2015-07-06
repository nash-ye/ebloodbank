<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Sessions;
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

                if (! empty($user_id) && $user_id !== Sessions::getCurrentUserID()) {
                    $deleted = Models\Users::delete($user_id);

                    redirect(getSiteURL(array(
                        'page' => 'manage-users',
                        'flag-deleted' => $deleted,
                    )));
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
