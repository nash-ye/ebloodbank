<?php

namespace eBloodBank;

/**
 * @since 0.5.2
 */
class About_View extends Default_View {

	/**
	 * @return string
	 * @since 0.5.2
	 */
	public function get_title() {
		return 'حول المشروع';
	}

	/**
	 * @return void
	 * @since 0.5.2
	 */
	public function __invoke() {

		$this->template_header(); ?>

			<div id="project-description">
				<h3>وصف المشروع</h3>
				<p>نظام بنك الدم الإلكتروني، الهدف منه هو تنظيم قاعدة بيانات حول المتطوعين المستعدين للتبرع بالدم حسب المدن والمديريات وتوفير واجهة بسيطة للبحث والإضافة والإدارة. المشروع يوفر جهد ووقت كبير يضيع من المحتاجين للمتبرعبين في البحث وخصوصاً عندما يكون المحتاج من فصيلة دم نادرة.</p>
			</div>

			<div id="project-requirements">
				<h3>المتطلبات المادية</h3>
				<ul>
					<li>جهاز كمبيوتر، يعمل كمتسضيف للتطبيق (معالج: <span dir="ltr">Dual 2GHz+</span>، الذاكرة العشوائية: <span dir="ltr">2GB+</span>، المساحة التخزينية: <span dir="ltr">80GB</span>).</li>
					<li>موظفين، يقومون بعملية إدارة الموقع ومتابعته.</li>
				</ul>
				<h3>المتطلبات البرمجية</h3>
				<ul>
					<li><span dir="ltr">PHP 5.4+</span></li>
					<li><span dir="ltr">MySQL 5.6+</span></li>
					<li><span dir="ltr">Modern Browser (Google Chrome, Mozilla Firefox...etc)</span></li>
				</ul>
			</div>

			<div id="project-database">

				<h3>قاعدة بيانات المشروع</h3>
				<p>قاعدة بيانات المشروع تحتوي على 9 جداول. إستخدمنا نظام MySQL نظرا لكفائته وسهولة إستخدامه مع تطبيقات الويب. وتم تصميمها بناءً على المعلومات التي تم جمعها من النزول الميداني والمناقشة مع المستفيدين من المشروع، النسخة الحالية من قاعدة البيانات قادرة على تخزين البيانات الأكثر أهمية، بالإضافة إلى إستخدامنا فكرة الـ <a href="http://en.wikipedia.org/wiki/Entity%E2%80%93attribute%E2%80%93value_model">Open Schema</a> لجعل قاعدة البيانات مرنة، وقادرة على إستيعاب معظم التطويرات المستقبلية الممكنة بدون الحاجة إلى تغيير هيكلة قاعدة البيانات في كل مرة.</p>

				<figure>
					<a href="assets/img/db-eer.png" target="__blank">
						<img src="assets/img/db-eer.png" alt="EER Diagram" />
					</a>
					<figcaption>EER Diagram</figcaption>
				</figure>

				<figure>
					<a href="assets/img/db-er.jpg" target="__blank">
						<img src="assets/img/db-er.jpg" alt="ER Diagram" />
					</a>
					<figcaption>ER Diagram</figcaption>
				</figure>

				<h3>جداول قاعدة بيانات</h3>

				<table id="database-tables" class="data-table">
					<tr>
						<th>donor</th>
						<td><p>جدول المتبرعين، يحتوي على بيانات المتطوعين المستعدين للتبرع بالدم.</p></td>
					</tr>
					<tr>
						<th>donor_meta</th>
						<td><p>جدول البيانات الإضافية للمتبرعين، تطبيق لموديل الـ EAV. تكمن فائدته في حال رغبنا بإضافة طلب المزيد من البيانات من المتبرعين مستقبلا كرقم تلفون ثاني، أو رابط حساب الفيس بوك أو حتى يقوم بإخبارنا بالطريقة المفضلة له للتواصل، عبر التلفون أو البريد الإلكتروني؟! .. تعديل هيكلة قاعدة البيانات في كل مرة نرغب بها بإضافة ميزة معينة أمر مرهق وغير مجدي. لذا  فكرة هذا الجدول مفيدة جداً.</p></td>
					</tr>
					<tr>
						<th>user</th>
						<td><p>جدول المستخدمين، مدراء والمسئولين عن مراجعة بيانات المتطوعين ومتابعة تطويرات المشروع.</p></td>
					</tr>
					<tr>
						<th>user_meta</th>
						<td><p>جدول البيانات الإضافية للمستخدمين، نفس فكرة جدول الـ donor_meta أعلاه.</p></td>
					</tr>
					<tr>
						<th>city</th>
						<td><p>جدول المدن، يحتوي على قائمة المدن ليتم إستخدامه في فهرسة وترتيب المتبرعين بكفاءة عالية.</p></td>
					</tr>
					<tr>
						<th>district</th>
						<td><p>جدول المديريات، يحتوي على بيانات المديريات لكل مدينة ويتم إستخدامه في فهرسة وترتيب المتبرعين بكفاءة عالية.</p></td>
					</tr>
					<tr>
						<th>test_type</th>
						<td><p>جدول أنواع الفحوصات الطبية مع عناوينها ورقم يشير إلى أهمية الفحص الطبي.</p></td>
					</tr>
					<tr>
						<th>donor_test</th>
						<td><p>سجل للفحوصات الطبية للمتبرع، يستخدم لتأكيد حالة المتبرع الصحية ونوع فصيلة دمه وغيرها من الأسباب الطبية.</p></td>
					</tr>
					<tr>
						<th>donation</th>
						<td><p>سجل التبرعات لكل متبرع، أهميته تكمن في معرفة آخر وقت تبرع لكل متبرع والمساهمة في تحسين جودة البحث عن متبرعين بالدم.</p></td>
					</tr>
				</table>

			</div>

			<div>
				<h3>أمثلة لجمل الإستعلامات <abbr>SQL</abbr></h3>

				<b>جملة إنشاء قاعدة البيانات:</b>
				<pre class="code-snippet">
<code>
CREATE DATABASE `ebloodbank` CHARACTER SET utf8 COLLATE utf8_general_ci;
</code>
				</pre>

				<b>جملة إنشاء مستخدم قاعدة البيانات:</b>
				<pre class="code-snippet">
<code>
CREATE USER 'ebb'@'localhost' IDENTIFIED BY 'EBB#Team';
GRANT ALL PRIVILEGES ON ebloodbank.* TO 'ebb'@'localhost';
</code>
				</pre>

				<b>جمل إنشاء جداول قاعدة البيانات:</b>
				<pre class="code-snippet">
<code>
CREATE  TABLE `city` (
  `city_id` INT NOT NULL AUTO_INCREMENT ,
  `city_name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`city_id`) )

CREATE  TABLE `district` (
  `distr_id` INT NOT NULL AUTO_INCREMENT ,
  `distr_name` VARCHAR(255) NOT NULL ,
  `distr_city_id` INT NOT NULL ,
  PRIMARY KEY (`distr_id`) ,
  CONSTRAINT `district_city_id`
    FOREIGN KEY (`distr_city_id` )
    REFERENCES `city` (`city_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `dis_idx` ON `district` (`distr_city_id` ASC) ;

CREATE  TABLE `donor` (
  `donor_id` INT NOT NULL AUTO_INCREMENT ,
  `donor_name` VARCHAR(255) NOT NULL ,
  `donor_gender` VARCHAR(45) NOT NULL ,
  `donor_weight` SMALLINT NOT NULL ,
  `donor_birthdate` DATE NOT NULL ,
  `donor_blood_group` VARCHAR(45) NOT NULL ,
  `donor_distr_id` INT NOT NULL ,
  `donor_phone` VARCHAR(50) NOT NULL ,
  `donor_email` VARCHAR(100) NULL ,
  `donor_rtime` DATETIME NOT NULL ,
  `donor_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`donor_id`) ,
  CONSTRAINT `donor_district_id`
    FOREIGN KEY (`donor_distr_id` )
    REFERENCES `district` (`distr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `donor_district_id_idx` ON `donor` (`donor_distr_id` ASC) ;

CREATE  TABLE `donor_meta` (
  `meta_id` INT NOT NULL AUTO_INCREMENT ,
  `donor_id` INT NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `dm_donor_id`
    FOREIGN KEY (`donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `dm_donor_id_idx` ON `donor_meta` (`donor_id` ASC) ;

CREATE  TABLE `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_logon` VARCHAR(100) NOT NULL ,
  `user_pass` VARCHAR(100) NOT NULL ,
  `user_role` VARCHAR(45) NOT NULL ,
  `user_rtime` DATETIME NOT NULL ,
  `user_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`user_id`) )
CREATE UNIQUE INDEX `user_logon_UNIQUE` ON `user` (`user_logon` ASC) ;

CREATE  TABLE `user_meta` (
  `meta_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `meta_key` VARCHAR(45) NOT NULL ,
  `meta_value` LONGTEXT NULL ,
  PRIMARY KEY (`meta_id`) ,
  CONSTRAINT `um_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `um_user_id_idx` ON `user_meta` (`user_id` ASC) ;

CREATE  TABLE `test_type` (
  `tt_id` INT NOT NULL AUTO_INCREMENT ,
  `tt_title` VARCHAR(255) NOT NULL ,
  `tt_priority` INT NOT NULL DEFAULT 10 ,
  PRIMARY KEY (`tt_id`) )

CREATE  TABLE `donor_test` (
  `test_id` INT NOT NULL AUTO_INCREMENT ,
  `test_time` DATETIME NOT NULL ,
  `test_type_id` INT NOT NULL ,
  `test_donor_id` INT NOT NULL ,
  `test_document` BLOB NOT NULL ,
  `test_status` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`test_id`) ,
  CONSTRAINT `dt_donor_id`
    FOREIGN KEY (`test_donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dt_type_id`
    FOREIGN KEY (`test_type_id` )
    REFERENCES `test_type` (`tt_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `dt_donor_id_idx` ON `donor_test` (`test_donor_id` ASC) ;
CREATE INDEX `dt_type_id_idx` ON `donor_test` (`test_type_id` ASC) ;

CREATE  TABLE `donation` (
  `donat_id` INT NOT NULL AUTO_INCREMENT ,
  `donat_time` DATETIME NOT NULL ,
  `donat_purpose` VARCHAR(255) NULL ,
  `donat_donor_id` INT NOT NULL ,
  PRIMARY KEY (`donat_id`) ,
  CONSTRAINT `donat_donor_id`
    FOREIGN KEY (`donat_donor_id` )
    REFERENCES `donor` (`donor_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
CREATE INDEX `donat_donor_id_idx` ON `donation` (`donat_donor_id` ASC) ;
</code>
				</pre>

			</div>
				<?php

		$this->template_footer();

	}

}
