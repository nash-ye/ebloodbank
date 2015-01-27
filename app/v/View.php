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
				<link rel="stylesheet" type="text/css" href="assets/css/style.css">
			</head>

			<body>

			<div id="wrapper">

				<header id="header" role="banner">

					<div class="wrapper">

						<h1 id="site-title">بنك الدم الإلكتروني</h1>

						<nav id="primary-nav" role="navigation">

							<ul class="dropdown">
								<li><a href="?page=index">الرئيسية</a></li>
								<li><a href="?page=donors">المتبرعين</a></li>
								<li>
									<span>المدن والمديريات</span>
									<ul>
										<li><a href="?page=cites">المدن</a></li>
										<li><a href="?page=distrs">المديريات</a></li>
									</ul>
								</li>
								<li><a href="?page=users">المستخدمين</a></li>
							</ul>

					</div>

				</header>

				<section id="content">

					<div class="wrapper">

						<header id="page-header">
							<h1 id="page-title"><?php echo $this->get_title() ?></h1>
						</header><?php

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	protected function template_footer() { ?>

						</div>

				</section> <!-- #content -->

				<footer id="footer">

					<div class="wrapper">
					</div>

				</footer>

			</div> <!-- #wrapper -->

			</body>

		</html><?php

	}

}