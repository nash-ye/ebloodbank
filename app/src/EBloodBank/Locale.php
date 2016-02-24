<?php
/**
 * Locale class file
 *
 * @package eBloodBank
 * @since   1.0
 */
namespace EBloodBank;

use Gettext;
use InvalidArgumentException;

/**
 * Locale class
 *
 * @since 1.0
 */
class Locale
{
    /**
     * The locale code.
     *
     * @var string
     * @since 1.0
     */
    protected $code;

    /**
     * The locale MO file path.
     *
     * @var string
     * @since 1.0
     */
    protected $moFilePath;

    /**
     * The locale PO file path.
     *
     * @var string
     * @since 1.0
     */
    protected $poFilePath;

    /**
     * The locale translations.
     *
     * @var \Gettext\Translations
     * @since 1.0
     */
    protected $translations;

    /**
     * The locale language code.
     *
     * @var string
     * @since 1.0
     */
    protected $languageCode;

    /**
     * The locale text direction.
     *
     * @var string
     * @since 1.0
     */
    protected $textDirection;

    /**
     * @throws \InvalidArgumentException
     * @return void
     * @since 1.0
     */
    public function __construct($code, $moFile, $poFile = '')
    {
        if (! empty($code) && strlen($code) >= 2) {
            $this->code = $code;
        } else {
            throw new InvalidArgumentException(__('Invalid locale code.'));
        }

        if (! empty($moFile) && is_readable($moFile)) {
            $this->moFilePath = $moFile;
        } else {
            throw new InvalidArgumentException(__('Locale MO file is not readable or not exists.'));
        }

        if (! empty($poFile)) {
            if (is_readable($poFile)) {
                $this->poFilePath = $poFile;
            } else {
                throw new InvalidArgumentException(__('Locale PO file is not readable or not exists.'));
            }
        }
    }

    /**
     * Get the locale code.
     *
     * @return string
     * @since 1.0
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the locale MO file path.
     *
     * @return string
     * @since 1.0
     */
    public function getMOFilePath()
    {
        return $this->moFilePath;
    }

    /**
     * Get the locale PO file path.
     *
     * @return string
     * @since 1.0
     */
    public function getPOFilePath()
    {
        if (is_null($this->poFilePath)) {
            $moFilePath = $this->getMOFilePath();
            $poFilePath = dirname($moFilePath) . '/' . basename($moFilePath, '.mo') . '.po';
            if (is_readable($poFilePath)) {
                $this->poFilePath = $poFilePath;
            }
        }
        return $this->poFilePath;
    }

    /**
     * Get the locale translations.
     *
     * @return \Gettext\Translations
     * @since 1.0
     */
    public function getTranslations()
    {
        if (is_null($this->translations)) {
            $this->translations = Gettext\Extractors\Mo::fromFile($this->getMOFilePath());
        }

        return $this->translations;
    }

    /**
     * Get the language code.
     *
     * @return string
     * @since 1.0
     */
    public function getLanguageCode()
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
     * Get the text direction.
     *
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
