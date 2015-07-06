<?php
namespace eBloodBank\Controllers;

use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Sessions;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class Signin extends Controller
{
    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        if (isset($_REQUEST['action'])) {
            if ('signin' === $_REQUEST['action']) {
                if (Sessions::isSignedIn()) {
                    die(-1);
                }

                if (isset($_POST['user_logon'], $_POST['user_pass'])) {
                    $signed_in = Sessions::signIn($_POST['user_logon'], $_POST['user_pass']);

                    if ($signed_in) {
                        redirect('index.php');
                    }
                }
            } elseif ('signout' === $_REQUEST['action']) {
                if (Sessions::signOut()) {
                    redirect(URL);
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
		if (! Sessions::isSignedIn()) {
			$view = new View('signin');
			$view();
		}

    }
}
