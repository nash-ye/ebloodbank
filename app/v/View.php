<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
abstract class View {

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_title() {}

	/**
	 * @return string
	 * @since 0.5.6
	 */
	public function get_fa_icon() {}

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract public function template_header();

	/**
	 * @return void
	 * @since 0.1
	 */
	abstract public function template_footer();

}

/**
 * @since 0.1
 */
class Default_View extends View {

	/**
	 * @return void
	 * @since 0.1
	 */
	public function template_header() { ?>
		<!doctype html>
		<html lang="ar" dir="rtl">
			<head>
				<meta charset="UTF-8">
				<title><?php echo $this->get_title() ?></title>
				<meta name="designer" content="Nashwan Doaqan" />

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
									<li>
										<i class="fa fa-home"></i>
										<a href="<?php site_url() ?>">الرئيسية</a>
									</li>
									<li><a href="<?php site_url( array( 'page' => 'donors' ) ) ?>">المتبرعين</a></li>
									<li><a href="<?php site_url( array( 'page' => 'banks' ) ) ?>">بنوك الدم</a></li>
									<li>
										<span>المدن والمديريات</span>
										<ul>
											<li><a href="<?php site_url( array( 'page' => 'cites' ) ) ?>">المدن</a></li>
											<li><a href="<?php site_url( array( 'page' => 'distrs' ) ) ?>">المديريات</a></li>
										</ul>
									</li>
									<li><a href="<?php site_url( array( 'page' => 'users' ) ) ?>">المستخدمين</a></li>
									<li><a href="<?php site_url( array( 'page' => 'about' ) ) ?>">حول</a></li>
								</ul>

							</nav>

						</div>

					</header>

					<div id="container">

						<section id="content">

							<div class="wrapper">

								<header id="page-header">
									<h1 id="page-title"><?php printf( '%s &nbsp; %s', $this->get_fa_icon(), $this->get_title() ) ?></h1>
								</header><?php

	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function template_footer() { ?>

								</div>

						</section> <!-- #content -->

					</div> <!-- #container -->

					<footer id="footer">

						<div class="wrapper">

							<nav id="secondary-nav" role="navigation">

								<ul>
									<li>
										<i class="fa fa-home"></i>
										<a href="<?php site_url() ?>">الرئيسية</a>
									</li>
									<li>
										<i class="fa fa-dashboard"></i>
										<a href="<?php site_url( array( 'page' => 'dashboard' ) ) ?>">لوحة التحكم</a>
										<?php if ( Sessions::is_signed_in() ) : ?>
										<a href="<?php site_url( array( 'action' => 'signout' ) ) ?>">
											<span style="color:#333;">[تسجيل الخروج]</span>
										</a>
										<?php endif; ?>
									</li>
								</ul>

							</nav>

							<p class="copyrights">جميع الحقوق محفوظة</p>

						</div>

					</footer>

				</div> <!-- #wrapper -->

				<?php if ( method_exists( $this, 'hook_body' ) ) { $this->hook_body(); } ?>

			</body>

		</html><?php

	}

}