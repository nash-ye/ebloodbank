<?php

namespace eBloodBank;

/**
 * @since 0.1
 */
class FrontPage_View extends Default_View {

	/**
	 * @return string
	 * @since 0.1
	 */
	public function get_title() {
		return 'الرئيسية';
	}

	/**
	 * @return void
	 * @since 0.4
	 */
	public function hook_head() { ?>
		<script src="assets/js/jquery.js" type="text/javascript"></script>
		<link rel="stylesheet" href="assets/css/nivo-slider.css" type="text/css" />
		<?php
	}

	/**
	 * @return void
	 * @since 0.4
	 */
	public function hook_body() { ?>
		<script src="assets/js/jquery.nivo.slider.js" type="text/javascript"></script>
		<?php
	}

	/**
	 * @return void
	 * @since 0.1
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<div class="row">

				<div id="wrapper-filter-donors-1" class="column">

					<form id="form-filter-donors-1" action="<?php site_url( array( 'page' => 'donors' ) ) ?>" method="POST">

						<div>
							<label for="blood_group">فصيلة الدم</label>
							<select name="blood_group" id="blood_group">
								<option value="all">الكل</option>
								<?php foreach( Donor::$blood_groups as $blood_group ) : ?>
									<option><?php echo $blood_group ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="city_id">المدينة</label>
							<select name="city_id" id="city_id">
								<?php foreach( Cites::fetch_all() as $city ) : ?>
									<option value="<?php $city->display( 'city_id', 'attr' ) ?>"><?php $city->display( 'city_name' ) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="distr_id">المديرية</label>
							<select name="distr_id" id="distr_id">
								<option value="-1">الكل</option>
								<?php foreach( Districts::fetch_all() as $distr ) : ?>
									<option value="<?php $distr->display( 'distr_id', 'attr' ) ?>"><?php $distr->display( 'distr_name' ) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<input type="hidden" name="page" value="donors" />

						<div>
							<button type="submit">بحث</button>
						</div>

					</form>

				</div>

				<div id="wrapper-frontpage-slider" class="slider-wrapper theme-default column">

					<script type="text/javascript">
					jQuery(window).load(function() {
						jQuery('#frontpage-slider').nivoSlider({
							controlNav: false
						});
					});
					</script>

					<div id="frontpage-slider" class="nivoSlider">
						<img src="assets/img/slide-1.jpg" alt="" />
						<img src="assets/img/slide-2.jpg" alt="" />
					</div>

				</div>

			</div>

			<div id="blocks" class="row">

				<div id="block-1" class="column block">
					<h3>التبرع بالدم؟</h3>
					<p>هو اجراء طبي يكمن في نقل دم من شخص سليم معافى طوعاً إلى شخص مريض </p>
				</div>

				<div id="block-2" class="column block">
					<h3>فوائد التبرع بالدم؟</h3>
					<p>يساعد التبرع على تنشيط نخاع العظم في إنتاج خلايا دم جديدة تستطيع حمل كمية أكبر من الأوكسجين إلى أعضاء الجسم الرئيسية مثلًا (الدماغ ….يساعد على زيادة التركيز والنشاط في العمل وعدم الخمول).</p>
				</div>

				<div id="block-3" class="column block">
					<h3>سجل معنا كمتبرع دم!</h3>
					<p>ماذا تنتظر؟ بنقرة واحدة يمكنك التسجيل معنا كمتبرع للدم!. لربما تنقذ حياة شخص ما.<br /><br /><a href="<?php site_url( array( 'page' => 'add-donor' ) ) ?>" class="btn">التسجيل كمتبرع</a></p>
				</div>

			</div>

		<?php

		$this->template_footer();

	}

}
