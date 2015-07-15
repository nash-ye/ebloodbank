<?php
/**
 * Error 404
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

header('HTTP/1.0 404 Not Found');

$header = new View('header');
$header(array( 'title' => __('Error: Not Found') ));
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, Not Found') ?></p>
	</div>

<?php
$footer = new View('footer');
$footer();
