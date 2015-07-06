<?php
namespace eBloodBank\Controllers;

use eBloodBank\Models;
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
		$this->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

		if (! isVaildID($this->id)) {
			die('Invaild district ID');
		}

        if (isset($_POST['action']) && 'submit_distr' === $_POST['action']) {
            if (isCurrentUserCan('edit_distr')) {
                $distr_data = array();

                if (isset($_POST['distr_name'])) {
                    $distr_data['distr_name'] = filter_var($_POST['distr_name'], FILTER_SANITIZE_STRING);
                }

                if (isset($_POST['distr_city_id'])) {
                    $distr_data['distr_city_id'] = (int) $_POST['distr_city_id'];
                }

                $distr_id = Models\Districts::update($this->id, $distr_data);
                $submitted = isVaildID($distr_id);

                redirect(getSiteURL(array(
                    'page' => 'edit-distr',
					'id' => $this->id,
                    'flag-submitted' => $submitted,
                )));
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
