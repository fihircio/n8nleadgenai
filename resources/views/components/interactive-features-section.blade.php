@props([
    'id',
    'title',
    'description',
    'bgColor' => theme('interactiveFeaturesSection', 'bgColor'),
    'titleColor' => theme('interactiveFeaturesSection', 'titleColor'),
    'descriptionColor' => theme('interactiveFeaturesSection', 'descriptionColor'),
    'tabTextColor' => theme('interactiveFeaturesSection', 'tabTextColor'),
    'activeTabIconColor' => theme('interactiveFeaturesSection', 'activeTabIconColor'),
    'tabIconColor' => theme('interactiveFeaturesSection', 'tabIconColor'),
])

<section id="{{ $id }}" class="{{ $bgColor }} py-6"
    x-data="{
        activeTabId: '',
        tabs: [],
        showContent: true,
        async changeTab(newTabId) {
            if (this.activeTabId === newTabId) return;

            this.showContent = false;
            await new Promise(resolve => setTimeout(resolve, 100));
            this.activeTabId = newTabId;
            this.showContent = true;

            this.$nextTick(() => {
                const videoElement = this.$refs.videoPlayer;
                if (videoElement) {
                    const newVideoSrc = videoElement.querySelector('source').getAttribute('src');
                    videoElement.querySelector('source').src = newVideoSrc;
                    videoElement.load();
                }
            });
        },

        get activeTab() {
            return this.tabs.find(tab => tab.id === this.activeTabId);
        },
        init() {
            this.tabs = [...$refs.tabContainer.children].map((tab, index) => {
                const tabId = tab.getAttribute('data-tab-id');
                const icon = tab.querySelector('.tab-icon') ? tab.querySelector('.tab-icon').innerHTML : '';
                const content = tab.querySelector('[data-tab-content]').innerHTML;
                const mediaType = tab.getAttribute('data-media-type') || null;
                const mediaUrl = tab.getAttribute('data-media-url') || null;

                if (index === 0) {
                    this.activeTabId = tabId;
                }

                return {
                    id: tabId,
                    name: tab.getAttribute('data-name'),
                    icon: icon,
                    mediaType: mediaType,
                    mediaUrl: mediaUrl,
                    content: content,
                };
            });
        }
    }"
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title and Description -->
        <h2 class="text-4xl font-bold {{ $titleColor }} mb-4 text-center">{{ $title }}</h2>
        <p class="text-xl {{ $descriptionColor }} mb-8 text-center max-w-3xl mx-auto">{{ $description }}</p>

        <!-- Tabs Navigation -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-0 md:flex md:flex-wrap md:justify-center my-4">
            <template x-for="tab in tabs" :key="tab.id">
                <button
                    @click="changeTab(tab.id)"
                    :class="{ '{{ $activeTabIconColor }}': activeTabId === tab.id, '{{ $tabIconColor }}': activeTabId !== tab.id }"
                    class="flex flex-col items-center transition-all duration-100 ease-in-out md:m-3 md:!mx-6 md:max-w-min"
                >
                    <div x-html="tab.icon" class="w-8 h-8 mb-2"></div>
                    <span class="text-sm font-bold" x-text="tab.name"></span>
                </button>
            </template>
        </div>

        <style>
            /* Large screens (lg) - 1024px and larger */
            @media (min-width: 1024px) {
              .ml-custom {
                margin-left: 5rem; /* Customize for large screens */
              }
            }

            /* Extra large screens (xl) - 1280px and larger */
            @media (min-width: 1280px) {
              .ml-custom {
                margin-left: -15rem; /* Customize for extra large screens */
              }
            }
          </style>


        <!-- Tabs Content and Media -->
        <div class="flex flex-col justify-center xl:flex-row items-start my-7 min-h-[300px] {{ $tabTextColor }}">
            <!-- Content Area -->
            <div :class="{'md:w-2/3 md:ml-20 xl:w-auto xl:pr-8': activeTab.mediaType, 'ml-custom': !activeTab.mediaType}">
                <div
                    x-show="showContent"
                    x-transition:enter="transition-opacity ease-out duration-100"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak
                >
                    <div x-html="activeTab.content"></div>
                </div>
            </div>

            <!-- Media Area -->
            <template x-if="activeTab.mediaType">
                <div class="md:w-3/4 md:m-auto mt-6 md:mt-6 lg:mt-6 xl:w-1/2 xl:m-0 xl:mt-0">
                    <div
                        x-show="showContent"
                        x-transition:enter="transition-opacity ease-out duration-100"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-cloak
                    >
                        <!-- Media Rendering Logic -->
                        <template x-if="activeTab.mediaType === 'image'">
                            <img :src="activeTab.mediaUrl" alt="" class="h-auto rounded-lg shadow-lg">
                        </template>
                        <template x-if="activeTab.mediaType === 'video'">
                            <video autoplay muted loop x-ref="videoPlayer" class="h-auto rounded-lg shadow-lg" preload="metadata">
                                <source :src="activeTab.mediaUrl" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Hidden Tabs for Initialization -->
        <div x-ref="tabContainer" class="hidden">
            {{ $slot }}
        </div>
    </div>
</section>
