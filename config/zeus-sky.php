<?php

return [
    'domain' => null,

    /**
     * set the default path for the blog homepage.
     */
    'prefix' => env('BLOG_URL_PREFIX', 'page'),

    /**
     * the middleware you want to apply on all the blog routes
     * for example if you want to make your blog for users only, add the middleware 'auth'.
     */
    'middleware' => ['web'],

    /**
     * URI prefix for each content type
     */
    'uri' => [
        'post' => 'post',
        'page' => env('BLOG_PAGE_URL_PREFIX', ''),
        'library' => 'library',
        'faq' => env('BLOG_FAQ_URL_PREFIX', 'faq'),
    ],

    /**
     * you can overwrite any model and use your own
     * you can also configure the model per panel in your panel provider using:
     * ->skyModels([ ... ])
     */
    'models' => [
        'Faq' => \LaraZeus\Sky\Models\Faq::class,
        'Post' => \App\Models\Post::class,
        'PostStatus' => \App\Models\PostStatus::class,
        'Tag' => \LaraZeus\Sky\Models\Tag::class,
        'Library' => \LaraZeus\Sky\Models\Library::class,
        'Navigation' => \LaraZeus\Sky\Models\Navigation::class,
    ],

    'parsers' => [
        \LaraZeus\Sky\Classes\BoltParser::class,
    ],

    'recentPostsLimit' => 5,

    'searchResultHighlightCssClass' => 'highlight',

    'skipHighlightingTerms' => ['iframe'],

    'defaultFeaturedImage' => null,

    /**
     * the default editor for pages and posts, Available:
     * \LaraZeus\Sky\Editors\TipTapEditor::class,
     * \LaraZeus\Sky\Editors\TinyEditor::class,
     * \LaraZeus\Sky\Editors\MarkdownEditor::class,
     * \LaraZeus\Sky\Editors\RichEditor::class,
     */
    'editor' => \LaraZeus\Sky\Editors\RichEditor::class,
];
