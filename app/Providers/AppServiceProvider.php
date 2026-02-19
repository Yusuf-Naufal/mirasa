<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('translate', function ($expression) {
            return "<?php 
                \$text = $expression;
                \$targetLang = request()->get('lang', 'ID');

                if (\$targetLang === 'ID') {
                    echo \$text;
                } else {
                    \$cacheKey = 'trans_' . md5(\$text . \$targetLang);
                    // Perbaikan: gunakan \$cacheKey tanpa double backslash
                    echo \Illuminate\Support\Facades\Cache::rememberForever(\$cacheKey, function () use (\$text, \$targetLang) {
                        try {
                            \$translator = new \DeepL\Translator(env('DEEPL_AUTH_KEY'));
                            return \$translator->translateText(\$text, null, \$targetLang)->text;
                        } catch (\Exception \$e) {
                            return \$text;
                        }
                    });
                }
            ?>";
        });
    }
}
