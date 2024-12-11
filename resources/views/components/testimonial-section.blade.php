@props([
    'testimonials',
    'sectionTitle' => 'Testimonials',
    'bgColor' => theme('testimonialSection', 'bgColor'),
    'sectionPadding' => 'sm:py-12 md:py-16 py-5',
    'containerMaxWidth' => 'max-w-7xl',
    'gridCols' => 'md:grid-cols-2 lg:grid-cols-3',
    'gridGap' => 'gap-8',
    'cardBgColor' => theme('testimonialSection', 'cardBgColor'),
    'cardPadding' => 'p-6',
    'cardRounded' => 'rounded-lg',
    'cardShadow' => 'shadow-md',
    'titleColor' => theme('testimonialSection', 'titleColor'),
    'titleSize' => 'text-3xl',
    'titleWeight' => 'font-bold',
    'titleMargin' => 'mb-12',
    'titleAlignment' => 'text-center',
    'quoteColor' => theme('testimonialSection', 'quoteColor'),
    'quoteSize' => 'text-lg',
    'quoteMargin' => 'mb-6',
    'authorNameColor' => theme('testimonialSection', 'authorNameColor'),
    'authorNameSize' => 'text-lg',
    'authorNameWeight' => 'font-semibold',
    'authorTitleColor' => theme('testimonialSection', 'authorTitleColor'),
    'authorTitleSize' => 'text-sm',
    'avatarSize' => 'w-12 h-12',
    'avatarRounded' => 'rounded-full',
])

<section class="{{ $bgColor }} {{ $sectionPadding }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 {{ $containerMaxWidth }}">
        @if($sectionTitle)
            <h2 class="{{ $titleColor }} {{ $titleSize }} {{ $titleWeight }} {{ $titleMargin }} {{ $titleAlignment }}">
                {{ $sectionTitle }}
            </h2>
        @endif
        @if(count($testimonials) === 1)
            <div class="max-w-2xl mx-auto">
                <div class="{{ $cardBgColor }} {{ $cardPadding }} {{ $cardRounded }} {{ $cardShadow }}">
                    <blockquote class="{{ $quoteColor }} {{ $quoteSize }} {{ $quoteMargin }}">
                        "{{ $testimonials[0]['quote'] }}"
                    </blockquote>
                    <div class="flex items-center">
                        @if(isset($testimonials[0]['avatar']))
                            <img
                                src="{{ $testimonials[0]['avatar'] }}"
                                alt="{{ $testimonials[0]['name'] }}"
                                class="{{ $avatarSize }} {{ $avatarRounded }} object-cover mr-4"
                            >
                        @endif
                        <div>
                            <div class="{{ $authorNameColor }} {{ $authorNameSize }} {{ $authorNameWeight }}">
                                {{ $testimonials[0]['name'] }}
                            </div>
                            @if(isset($testimonials[0]['title']))
                                <div class="{{ $authorTitleColor }} {{ $authorTitleSize }}">
                                    {{ $testimonials[0]['title'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 {{ count($testimonials) === 2 ? 'md:grid-cols-2' : 'md:grid-cols-2 lg:grid-cols-3' }} {{ $gridGap }} {{ count($testimonials) < 3 ? 'max-w-4xl mx-auto' : '' }} auto-rows-fr">
                @foreach($testimonials as $testimonial)
                    <div class="{{ $cardBgColor }} {{ $cardPadding }} {{ $cardRounded }} {{ $cardShadow }}">
                        <blockquote class="{{ $quoteColor }} {{ $quoteSize }} {{ $quoteMargin }}">
                            "{{ $testimonial['quote'] }}"
                        </blockquote>
                        <div class="flex items-center">
                            @if(isset($testimonial['avatar']))
                                <img
                                    src="{{ $testimonial['avatar'] }}"
                                    alt="{{ $testimonial['name'] }}"
                                    class="{{ $avatarSize }} {{ $avatarRounded }} object-cover mr-4"
                                >
                            @endif
                            <div>
                                <div class="{{ $authorNameColor }} {{ $authorNameSize }} {{ $authorNameWeight }}">
                                    {{ $testimonial['name'] }}
                                </div>
                                @if(isset($testimonial['title']))
                                    <div class="{{ $authorTitleColor }} {{ $authorTitleSize }}">
                                        {{ $testimonial['title'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
