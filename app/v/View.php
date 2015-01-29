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
				<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
				<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
				<?php if ( method_exists( $this, 'hook_head' ) ) { $this->hook_head(); } ?>
			</head>

			<body id="page-<?php echo CURRENT_PAGE ?>">

				<div id="wrapper">

					<header id="header" role="banner">

						<div class="wrapper">

							<h1 id="site-title">بنك الدم الإلكتروني</h1>

							<nav id="primary-nav" role="navigation">

								<ul class="dropdown">
									<li><a href="<?php site_url() ?>">الرئيسية</a></li>
									<li><a href="<?php site_url( array( 'page' => 'donors' ) ) ?>">المتبرعين</a></li>
									<li>
										<span>المدن والمديريات</span>
										<ul>
											<li><a href="<?php site_url( array( 'page' => 'cites' ) ) ?>">المدن</a></li>
											<li><a href="<?php site_url( array( 'page' => 'distrs' ) ) ?>">المديريات</a></li>
										</ul>
									</li>
									<li><a href="<?php site_url( array( 'page' => 'users' ) ) ?>">المستخدمين</a></li>
								</ul>

							</nav>

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

							<?php if ( Sessions::is_signed_in() ) : ?>
								<p><?php printf( '<b>%s</b>، <a href="%s">تسجيل الخروج</a>', Sessions::get_current_user()->get( 'user_logon' ), get_site_url( array( 'page' => 'signin', 'action' => 'signout' ) ) ) ?></p>
							<?php else: ?>
								<p>مجهول، <a href="<?php site_url( array( 'page' => 'signin' ) ) ?>">تسجيل الدخول</a></p>
							<?php endif; ?>

						</div>

					</footer>

				</div> <!-- #wrapper -->

				<?php if ( method_exists( $this, 'hook_body' ) ) { $this->hook_body(); } ?>

			</body>

		</html><?php

	}

}