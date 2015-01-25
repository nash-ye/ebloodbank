<?php

namespace eBloodBank;

/**
 * @since 0.2
 */
class User_View extends Default_View {

	/**
	 * @return string
	 * @since 0.2
	 */
	public function get_title() {
		return 'eBloodBank';
	}

	/**
	 * @return void
	 * @since 0.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

		<div id="content">

			<form id="form-user" class="form-block" method="POST">

				<div>
					<label for="user_logon">اسم الدخول</label>
					<input type="text" name="user_logon" id="user_logon" required="required" />
				</div>

				<div>
					<label for="user_pass">كلمة المرور</label>
					<input type="password" name="user_pass" id="user_pass" required="required" />
				</div>

				<div>
					<label for="user_role">الرتبة</label>
					<select name="user_role" id="user_role">
						<option value="administrator">مدير</option>
					</select>
				</div>

				<div>
					<button type="submit">ارسال</button>
				</div>

				<input type="hidden" name="action" value="submit_user" />

			</form>

		</div> <!-- #content --><?php

		$this->template_footer();

	}

}
