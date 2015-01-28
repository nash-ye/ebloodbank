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

				<div id="wrapper-filter-donors" class="column">

					<form id="form-filter-donors" method="GET">

						<div>
							<label for="blood_group">فصيلة الدم</label>
							<select name="blood_group" id="blood_group">
								<option value="all">الكل</option>
								<?php foreach( get_blood_groups() as $distr ) : ?>
									<option><?php echo $distr ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="city_id">المدينة</label>
							<select name="city_id" id="city_id">
								<?php foreach( Cites::fetch_all() as $distr ) : ?>
									<option value="<?php $distr->display( 'city_id', 'attr' ) ?>"><?php $distr->display( 'city_name' ) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="distr_id">المديرية</label>
							<select name="distr_id" id="distr_id">
								<option value="-1"></option>
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

		<?php

		$this->template_footer();

	}

}
