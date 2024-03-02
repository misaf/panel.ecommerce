<?php

declare(strict_types=1);

namespace App\Contract;

use App\Models\Language\Language as LanguageLanguage;

abstract class Language
{
    abstract public function get(): LanguageLanguage;
}
