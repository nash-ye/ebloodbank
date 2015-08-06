<?php
/**
 * Error 404
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

header('HTTP/1.0 404 Not Found');

$header = new View('header', array( 'title' => __('Error: Not Found') ));
$header();
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, Not Found') ?></p>
	</div>

<?php
$footer = new View('footer');
$footer();
