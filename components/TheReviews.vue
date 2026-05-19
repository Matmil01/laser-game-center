<template>
    <div class="">
        <div class="max-w-[1200px] mx-auto">
            <div class="relative">
                <h2 class="text-2xl sm:text-4xl font-bold text-white text-center mb-6">{{ $t('anmeldelser.title') }}</h2>
                <!-- Loading "skelet" :) -->
                <div
                    v-if="pending"
                    class="flex gap-6 p-6 overflow-x-auto -mt-6 max-w-full"
                >
                    <div
                        v-for="i in 3"
                        :key="`skeleton-${i}`"
                        class="bg-white/50 flex flex-col p-4 rounded-3xl min-w-[80vw] max-w-[90vw] sm:min-w-[350px] sm:max-w-[400px] lg:min-w-[400px] lg:max-w-[400px] animate-pulse"
                    >
                        <div class="flex justify-between mb-3">
                            <div
                                class="h-6 bg-hypnoblack/10 rounded w-32"
                            ></div>
                            <div class="h-8 w-24"></div>
                        </div>
                        <div class="space-y-2 flex-1">
                            <div
                                class="h-4 bg-hypnoblack/10 rounded w-full"
                            ></div>
                            <div
                                class="h-4 bg-hypnoblack/10 rounded w-full"
                            ></div>
                            <div
                                class="h-4 bg-hypnoblack/10 rounded w-3/4"
                            ></div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <div
                                class="h-5 w-20 bg-hypnoblack/10 rounded"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div
                    v-else-if="reviews.length > 0"
                    ref="scrollContainer"
                    class="flex gap-6 p-6 overflow-x-auto -mt-6 max-w-full scroll-smooth scrollbar-hide"
                    @mouseenter="isPaused = true"
                    @mouseleave="isPaused = false"
                    @touchstart="isPaused = true"
                    @touchend="isPaused = false"
                >
                    <div
                        v-for="(review, idx) in infiniteReviews"
                        :key="idx"
                        class="flex flex-col p-4 border-neon-neonred transition-transform duration-200 hover:scale-105 min-w-[80vw] max-w-[90vw] sm:min-w-[350px] sm:max-w-[400px] lg:min-w-[400px] lg:max-w-[400px]"
                    >
                        <div class="flex justify-between">
                            <h4 class="text-white font-light text-xl w-12">
                                {{ review.name }}
                            </h4>
                             <div class="flex -mr-4 -mt-4">
                                 <img src="/icons/Star.png" alt="Black star icon" class="w-15 h-15 -mr-8" />
                                 <img src="/icons/Star.png" alt="Black star icon" class="w-15 h-15 -mr-8" />
                                 <img src="/icons/Star.png" alt="Black star icon" class="w-15 h-15 -mr-8" />
                                 <img src="/icons/Star.png" alt="Black star icon" class="w-15 h-15 -mr-8" />
                                 <img src="/icons/Star.png" alt="Black star icon" class="w-15 h-15" />

                             </div>
                        </div>
                        <p class="text-white font-light text-content">
                            {{ review.comment }}
                        </p>
                    </div>
                </div>

                <!-- Error state -->
                <div v-else-if="error" class="flex gap-6 p-6 -mt-6">
                    <div
                        class="bg-white/50 flex flex-col p-4 rounded-3xl min-w-[80vw] sm:min-w-[350px] lg:min-w-[400px] text-center"
                    >
                        <p class="text-white font-light">
                            Kunne ikke indlæse anmeldelser. Prøv igen senere.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import reviewsData from "@/assets/data/reviews.json";

const pending = ref(false);
const error = ref(null);

const reviews = computed(() => reviewsData || []);
const infiniteReviews = computed(() => [...reviews.value, ...reviews.value]);

const scrollContainer = ref(null);
const isPaused = ref(false);
let scrollAmount = 0;
let animationStarted = false;

function startAutoScroll() {
    if (animationStarted) return;
    const container = scrollContainer.value;
    if (!container) return;
    animationStarted = true;

    const scrollStep = 0.3;

    function autoScroll() {
        if (!container) return;
        if (!isPaused.value) {
            scrollAmount += scrollStep;
            container.scrollLeft = scrollAmount;
            if (scrollAmount >= container.scrollWidth / 2) {
                scrollAmount = 0;
                container.scrollLeft = 0;
            }
        } else {
            scrollAmount = container.scrollLeft;
        }
        requestAnimationFrame(autoScroll);
    }
    autoScroll();
}

onMounted(() => {
    if (reviews.value.length > 0) {
        startAutoScroll();
    }
});

watch(scrollContainer, (el) => {
    if (el) startAutoScroll();
});
</script>

<style scoped>
.text-content {
    white-space: pre-line;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>
