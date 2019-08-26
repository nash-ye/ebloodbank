<?php
/**
 * Error 403 page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

http_response_code(403); // Set the HTTP response status.

$view->displayView('header', ['title' => d__('winry', 'Error: Access denied')]);
?>

	<div class="error-msg error-403-msg">
		<p><?= EBB\escHTML(d__('winry', 'Sorry, you are not authorized to access this page.')) ?></p>
	</div>

<?php
$view->displayView('footer');
