<?php
/**
 * Settings Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;
use EBloodBank\Roles;
use EBloodBank\Options;
use EBloodBank\Locales;

$view->displayView('header', ['title' => __('Settings')]);
?>

    <?php $view->displayView('notices') ?>

    <form id="form-settings" class="form-horizontal" method="POST">

        <fieldset>

            <legend><?= EBB\escHTML(__('Site Options')) ?></legend>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="site_url"><?= EBB\escHTML(__('Site URL')) ?> <span class="form-required">*</span></label>
                </div>
                <div class="col-sm-4">
                    <input type="url" name="site_url" id="site_url" class="form-control" value="<?= EBB\escURL(Options::getOption('site_url')) ?>" required />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="site_name"><?= EBB\escHTML(__('Site Name')) ?> <span class="form-required">*</span></label>
                </div>
                <div class="col-sm-4">
                    <input type="text" name="site_name" id="site_name" class="form-control" value="<?= EBB\escAttr(Options::getOption('site_name')) ?>" required />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="site_slogan"><?= EBB\escHTML(__('Site Slogan')) ?></label>
                </div>
                <div class="col-sm-4">
                    <input type="text" name="site_slogan" id="site_slogan" class="form-control" value="<?= EBB\escAttr(Options::getOption('site_slogan')) ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="site_email"><?= EBB\escHTML(__('Site E-mail')) ?> <span class="form-required">*</span></label>
                </div>
                <div class="col-sm-4">
                    <input type="email" name="site_email" id="site_email" class="form-control" value="<?= EBB\escAttr(Options::getOption('site_email')) ?>" required />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="site_locale"><?= EBB\escHTML(__('Site Locale')) ?></label>
                </div>
                <div class="col-sm-4">
                    <select name="site_locale" id="site_locale" class="form-control">
                        <option></option>
                        <?php foreach (Locales::getAvailableLocales() as $locale) : ?>
                        <option<?= EBB\toAttributes(['selected' => Locales::isCurrentLocale($locale)]) ?>><?= EBB\escHTML($locale->getCode()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </fieldset>

        <fieldset>

            <legend><?= EBB\escHTML(__('Users Options')) ?></legend>

            <div class="form-group">
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input<?= EBB\toAttributes(['type' => 'checkbox', 'name' => 'self_registration', 'id' => 'self_registration', 'value' => 'on', 'checked' => ('on' === Options::getOption('self_registration'))]) ?>/>
                            <?= EBB\escHTML(__('Enable self-registration.')) ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="new_user_role"><?= EBB\escHTML(__('New User Role')) ?></label>
                </div>
                <div class="col-sm-4">
                    <select name="new_user_role" id="new_user_role" class="form-control">
                        <?php $newUserRole = Options::getOption('new_user_role') ?>
                        <?php foreach (Roles::getRoles() as $role) : ?>
                        <option<?= EBB\toAttributes(['value' => $role->getSlug(), 'selected' => $role->getSlug() === $newUserRole]) ?>><?= EBB\escHTML($role->getTitle()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="new_user_status"><?= EBB\escHTML(__('New User Status')) ?></label>
                </div>
                <div class="col-sm-4">
                    <select name="new_user_status" id="new_user_status" class="form-control">
                        <?php $newUserStatus = Options::getOption('new_user_status') ?>
                        <?php foreach (['pending' => __('Pending'), 'activated' => __('Activated')] as $slug => $title) : ?>
                        <option<?= EBB\toAttributes(['value' => $slug, 'selected' => $slug === $newUserStatus]) ?>><?= EBB\escHTML($title) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

        </fieldset>

        <fieldset>

            <legend><?= EBB\escHTML(__('Reading Options')) ?></legend>

            <div class="form-group">
                <div class="col-sm-2">
                    <label for="entities_per_page"><?= EBB\escHTML(__('Entities Per Page')) ?> <span class="form-required">*</span></label>
                </div>
                <div class="col-sm-4">
                    <input type="number" name="entities_per_page" id="entities_per_page" class="form-control" value="<?= EBB\escAttr(Options::getOption('entities_per_page')) ?>" min="1" required />
                </div>
            </div>

        </fieldset>

        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary"><?= EBB\escHTML(__('Save Settings')) ?></button>
            </div>
        </div>

        <input type="hidden" name="action" value="save_settings" />

    </form>

<?php
$view->displayView('footer');
