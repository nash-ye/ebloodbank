<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Cites_View extends Default_View {

	/**
	 * @return string
	 * @since 0.3
	 */
	public function get_title() {
		return 'المدن';
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<a href="?page=add-city">
				<button type="button">أضف مدينة جديدة</button>
			</a>

			<table id="table-cites" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
				</thead>

				<tbody>

					<?php

						$cites = Cites::fetch_all();

						if ( ! empty( $cites ) && is_array( $cites ) ) {

							foreach( $cites as $city ) {

								?>

								<tr>
									<td><?php $city->display( 'city_id' ) ?></td>
									<td><?php $city->display( 'city_name' ) ?></td>
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
