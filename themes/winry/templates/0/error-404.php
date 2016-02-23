<?php
/**
 * Error 404 page template
 *
 * @package    Winry Theme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

http_response_code(404); // Set the HTTP response status.

$view->displayView('header', ['title' => d__('winry', 'Error: Not Found')]);
?>

	<div class="error-msg error-404-msg">
		<p><?= EBB\escHTML(d__('winry', 'Sorry, the page you requested was not found.')) ?></p>
	</div>

<?php
$view->displayView('footer');
