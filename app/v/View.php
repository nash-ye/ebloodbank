<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
abstract class View {

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract protected function template_header();

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract protected function template_footer();

}

/**
 * @since 0.1
 */
class Default_View extends View {

	/**
	 * @return void
	 * @since 0.1
	 */
	protected function template_header() { ?>
		<!doctype html>
		<html lang="ar" dir="rtl">
		  <head>
			<meta charset="UTF-8">
			<title><?php echo $this->get_title() ?></title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<link rel="stylesheet" type="text/css" href="/assets/style.css">
		  </head>

		  <body>

			<div id="wrapper">

				<header id="header">

				</header><?php

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	protected function template_footer() { ?>

				</div> <!-- #wrapper -->

			</body>

		</html><?php

	}

}