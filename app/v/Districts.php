<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Districts_View extends Default_View {

	/**
	 * @return string
	 * @since 0.3
	 */
	public function get_title() {
		return 'المديريات';
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<a href="?page=add-distr">
				<button type="button">أضف مديرية جديدة</button>
			</a>

			<table id="table-distrs" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>المدينة</th>
				</thead>

				<tbody>

					<?php

						$distrs = Districts::fetch_all();

						if ( ! empty( $distrs ) && is_array( $distrs ) ) {

							foreach( $distrs as $distr ) {

								?>

								<tr>
									<td><?php $distr->display( 'distr_id' ) ?></td>
									<td><?php $distr->display( 'distr_name' ) ?></td>
									<td>
										<?php

											$city = Cites::fetch_by_ID( $distr->get( 'distr_city_id' ) );
											$city->display( 'city_name' );

										?>
									</td>
								</tr>

								<?php

							}

						}

					?>

				</tbody>

			</table><?php

		$this->template_footer();

	}

}
