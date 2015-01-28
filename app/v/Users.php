<?php

namespace eBloodBank;

/**
 * @since 0.3
 */
class Users_View extends Default_View {

	/**
	 * @return string
	 * @since 0.3
	 */
	public function get_title() {
		return 'المستخدمين';
	}

	/**
	 * @return void
	 * @since 0.3
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<a href="?page=add-user">
				<button type="button">أضف مستخدم جديد</button>
			</a>

			<table id="table-users" class="list-table">

				<thead>
					<th>#</th>
					<th>الاسم</th>
					<th>الرتبة</th>
				</thead>

				<tbody>

					<?php

						$users = Users::fetch_all();

						if ( ! empty( $users ) && is_array( $users ) ) {

							foreach( $users as $user ) {

								?>

								<tr>
									<td><?php $user->display( 'user_id' ) ?></td>
									<td><?php $user->display( 'user_logon' ) ?></td>
									<td>
										<?php
											$user_role = Roles::get_role( $user->get( 'user_role' ) );
											echo ( $user_role ) ? $user_role->title : $user->get( 'user_role' );
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
