<?php
namespace eBloodBank\Controllers;

use eBloodBank\Kernal\View;
use eBloodBank\Kernal\Controller;
use eBloodBank\Models\Cites;

/**
 * @since 1.0
 */
class EditCity extends Controller
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
			die('Invaild city ID');
		}

        if (isset($_POST['action']) && 'submit_city' === $_POST['action']) {
            if (isCurrentUserCan('edit_city')) {
                $city_data = array();

                if (isset($_POST['city_name'])) {
                    $city_data['city_name'] = filter_var($_POST['city_name'], FILTER_SANITIZE_STRING);
                }

                $city_id = Cites::update($this->id, $city_data);
                $submitted = isVaildID($city_id);

                redirect(getSiteURL(array(
                    'page' => 'edit-city',
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
        if (isCurrentUserCan('edit_city')) {
            $view = new View('edit-city');
			$view(array( 'id' => $this->id ));
        } else {
            $view = new View('error-401');
			$view();
        }

    }
}
