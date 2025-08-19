<?php

namespace Modules\Core\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;

trait Translatable
{
    /**
     * Translate content to a specific language using Google Translate.
     */
    public function autoGoogleTranslator(string $targetLang, string $content): string
    {
        $translator = new GoogleTranslate;

        return $translator->setTarget($targetLang)->translate($content);
    }

    /**
     * Return all supported languages except the current locale.
     */
    public function otherLangs(): array
    {
        $locale = app()->getLocale();
        $supportedLocales = ['ar', 'en'];

        return array_values(array_filter($supportedLocales, fn ($lang) => $lang !== $locale));
    }
}
