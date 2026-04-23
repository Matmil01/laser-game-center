<template>
    <div
        ref="scrollContainer"
        class="flex gap-4 overflow-x-auto hide-scrollbar"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
        @touchstart="isPaused = true"
        @touchend="isPaused = false"
    >
        <!-- Render duplicated items to create a seamless infinite scroll effect -->
        <div class="flex-none" v-for="(foto, index) in duplicatedFotos" :key="index">
            <img class="h-52 max-w-fit object-cover" :src="foto.src" :alt="foto.alt" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import fotosData from "../assets/data/fotos.json";

const scrollContainer = ref(null);
const isPaused = ref(false);
let scrollAmount = 0;
let animationStarted = false;

// Duplicate the array so when we scroll past the original set,
// the second set is visible, letting us snap back to 0 seamlessly.
const duplicatedFotos = computed(() => [...fotosData, ...fotosData]);

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

            // Check if we've scrolled exactly halfway (the length of the original list)
            if (scrollAmount >= container.scrollWidth / 2) {
                // Reset back to the start
                scrollAmount = 0;
                container.scrollLeft = 0;
            }
        } else {
            // Keep track of the current scroll position if user scrolls manually while paused
            scrollAmount = container.scrollLeft;
        }
        requestAnimationFrame(autoScroll);
    }

    // Kick off animation
    autoScroll();
}

onMounted(() => {
    if (fotosData.length > 0) {
        // A short timeout ensures images and layout are fully rendered
        // so container.scrollWidth calculates accurately.
        setTimeout(() => {
            startAutoScroll();
        }, 100);
    }
});

watch(scrollContainer, (el) => {
    if (el) startAutoScroll();
});
</script>

<style scoped>
/* Hide scrollbar for an aesthetic look, while keeping it functionally scrollable */
.hide-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}
</style>
