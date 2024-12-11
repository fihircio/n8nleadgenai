<?php

namespace App;

use Illuminate\Support\Str;
use LaraZeus\Sky\SkyPlugin;

class CustomRenderNavItem
{
    public static function render(array $item, string $class = ''): string
    {
        $color = 'font-semibold';

        $uriPage = empty(config('zeus-sky.uri.page')) ? '' : config('zeus-sky.uri.page') . '/';
        $prefix = config('zeus-sky.prefix') . '/';
        $currentPage = str_replace($prefix . $uriPage, '', request()->path());

        if ($item['type'] === 'page-link' || $item['type'] === 'page_link') {
            $page = SkyPlugin::get()->getModel('Post')::page()->whereDate('published_at', '<=', now())->find($item['data']['page_id']) ?? '';
            $activeClass = $page->slug === $currentPage ? $color : 'border-transparent';

            return '<a class="' . $class . ' ' . $activeClass . '"
                        target="' . ($item['data']['target'] ?? '_self') . '"
                        href="' . route('page', $page) . '"' .
                when(config('saashovel.SPA_UX'), 'wire:navigate') . '
                    >' .
                $item['label'] .
                '</a>';
        } elseif ($item['type'] === 'post-link' || $item['type'] === 'post_link') {
            $post = SkyPlugin::get()->getModel('Post')::find($item['data']['post_id']) ?? '';
            $activeClass = (request()->routeIs('post', $post)) ? $color : 'border-transparent';

            return '<a class="' . $class . ' ' . $activeClass . '"
                    target="' . ($item['data']['target'] ?? '_self') . '"
                    href="' . route('post', $post) . '"
                >' .
                $item['label'] .
                '</a>';
        } elseif ($item['type'] === 'library-link' || $item['type'] === 'library_link') {
            $tag = SkyPlugin::get()->getModel('Tag')::find($item['data']['library_id']) ?? '';
            $activeClass = (str(request()->url())->contains($tag->library->first()->slug)) ? $color : 'border-transparent';

            return '<a class="' . $class . ' ' . $activeClass . '"
                    target="' . ($item['data']['target'] ?? '_self') . '"
                    href="' . route('library.tag', $tag->slug) . '"
                >' .
                $item['label'] .
                '</a>';
        } else {
            $activeClass = Str::slug($item['data']['url']) === $currentPage ? $color : 'border-transparent';
            return '<a  class="' . $class . ' ' . $activeClass . '"
                        target="' . ($item['data']['target'] ?? '_self') . '"
                        href="' . optional($item['data'])['url'] . '"' .
                        ((!empty($item['data']['target']) || str_contains($item['data']['url'], '#')) ? '' : when(config('saashovel.SPA_UX'), 'wire:navigate')) .
                        '>' .
                        $item['label'] .
                    '</a>';
        }
    }
}
