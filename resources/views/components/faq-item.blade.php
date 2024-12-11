@props([
    'question',
    'answer',
    'questionClass' => theme('faqItem', 'questionClass'),
    'answerClass' => theme('faqItem', 'answerClass'),
    'borderClass' => theme('faqItem', 'borderClass'),
])

<div x-data="{ isOpen: false }" class="py-4 {{ $borderClass }}">
    <div class="flex justify-between items-center cursor-pointer" @click="isOpen = !isOpen">
        <h4 class="select-none {{ $questionClass }}">{{ $question }}</h4>
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition transform rotate-0"><path d="M9 5l7 7-7 7"></path></svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition transform rotate-90"><path d="M9 5l7 7-7 7"></path></svg>
    </div>
    <p x-show="isOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="{{ $answerClass }}">{{ $answer }}</p>
</div>
