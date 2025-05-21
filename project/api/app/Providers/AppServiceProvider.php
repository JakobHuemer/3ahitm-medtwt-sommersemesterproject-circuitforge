<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;

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

        // https://socialiteproviders.com/Discord/#installation-basic-usage
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('discord', \SocialiteProviders\Discord\Provider::class);
        });

        define("TIPTAP_CONTENT", TiptapValidation::content()
            ->whitelist()
            ->nodes(
                "heading",
                "text",
                "paragraph",
                "blockquote",
                "codeBlock",
                "bulletList",
                "listItem",
                "orderedList"
            )
            ->marks(
                "bold",
                "italic",
                "underline",
                "strike",
                "code",
                "hashtag"
            ));

    }
}
