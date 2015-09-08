<?php
/**
 * Locale Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

use Gettext;

/**
 * @since 1.0
 */
class Locale
{
    /**
     * @var string
     * @since 1.0
     */
    protected $code;

    /**
     * @var \Gettext\Translations
     * @since 1.0
     */
    protected $translations;

    /**
     * @var string
     * @since 1.0
     */
    protected $languageCode;

    /**
     * @var string
     * @since 1.0
     */
    protected $textDirection;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \Gettext\Translations
     * @since 1.0
     */
    public function getTranslations()
    {
        if (is_null($this->translations)) {

            $code = $this->getCode();

            foreach (array( 'mo', 'po' ) as $fileExtension) {

                $filePath = EBB_APP_DIR . "/locales/{$code}.{$fileExtension}";

                if (is_readable($filePath)) {

                    switch ($fileExtension) {

                        case 'mo':
                            $this->translations = Gettext\Extractors\Mo::fromFile($filePath);
                            break;

                        case 'po':
                            $this->translations = Gettext\Extractors\Po::fromFile($filePath);
                            break;

                    }

                }

                if ( ! empty($this->translations)) {
                    break;
                }

            }

        }

        return $this->translations;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getLangaugeCode()
    {
        if (is_null($this->languageCode)) {

            $translations = $this->getTranslations();

            if (! empty($translations)) {

                $translation = $translations->find('language code', 'en');

                if (! empty($translation) && $translation->hasTranslation()) {
                    $this->languageCode = $translation->getTranslation();
                } elseif ($translations->hasLanguage()) {
                    $this->languageCode = $translations->getLanguage();
                }

            }

        }

        return $this->languageCode;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getTextDirection()
    {
        if (is_null($this->textDirection)) {

            $translations = $this->getTranslations();

            if (! empty($translations)) {

                $translation = $translations->find('text direction', 'ltr');

                if (! empty($translation) && $translation->hasTranslation()) {
                    $this->textDirection = $translation->getTranslation();
                }

            }

            if (empty($this->textDirection)) {
                $this->textDirection = 'ltr'; // The default text direction.
            }

        }

        return $this->textDirection;
    }
}
