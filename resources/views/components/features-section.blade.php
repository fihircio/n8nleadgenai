@props(['showSections' => false])

<div :class="{ 'hidden': !showSections }" x-cloak>
    {{ $slot }}
</div>
