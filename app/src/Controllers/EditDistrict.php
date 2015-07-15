<?php
namespace eBloodBank\Controllers;

use eBloodBank\EntityManager;
use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;

/**
 * @since 1.0
 */
class EditDistrict extends Controller
{
    /**
     * @var int
     * @since 1.0
     */
    protected $id = 0;

    /**
     * @return void
     * @since 1.0
     */
    public function processRequest()
    {
        $this->id = (int) $_GET['id'];

        if (! isVaildID($this->id)) {
            die('Invaild district ID');
        }

        if (isset($_POST['action']) && 'submit_distr' === $_POST['action']) {
            if (isCurrentUserCan('edit_distr')) {

                $em = EntityManager::getInstance();
                $distr = $em->getDistrictReference($this->id);

                if (isset($_POST['distr_name'])) {
                    $distr->set('distr_name', $_POST['distr_name'], true);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr->set('distr_city_id', $_POST['distr_city_id'], true);
                }

                $em->flush();
                $submitted = isVaildID($distr->get('distr_id'));

                redirect(getPageURL('edit-distr', array( 'id' => $this->id, 'flag-submitted' => $submitted )));

            }
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function outputResponse()
    {
        if (isCurrentUserCan('edit_distr')) {
            $view = new View('edit-district');
            $view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
            $view();
        }
    }
}
