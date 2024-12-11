@props([
  'text' => 'Get Started',
  'class' => 'text-white bg-lime-600 hover:bg-lime-700',
  'wire' => null,
  'alpine' => null,
])

<div {{ $attributes->merge(['class' => 'text-center my-6 bg-transparent']) }}>
  <a
      class="relative cursor-pointer select-none px-6 py-3 rounded-md text-lg font-semibold transition duration-300 {{ $class }}"
      @if($wire) wire:click="{{ $wire }}" @endif
      @if($alpine) x-on:click="window.location.href = '{{ $alpine }}'" @endif
  >
      {{ $text }}
    </a>
</div>
