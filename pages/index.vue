<template>
<div id="top" class="bg-black min-h-screen justify-center">
    <div class="sm:h-142.5 h-90">
        <div
            class="absolute mt-15 top-0 left-0 w-full sm:h-142.5 h-90 overflow-hidden z-0"
        >
            <img
                src="/photos/hero.webp"
                aria-hidden="true"
                class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none blur-sm scale-105"
            />
            <video
                src="/videos/hero.mp4"
                autoplay
                loop
                muted
                playsinline
                preload="none"
                class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none"
                style="min-width: 100%; min-height: 320px"
            ></video>
        </div>
        <div class="relative z-10 flex items-center justify-center h-full">
            <h1
                class="mt-64 text-white text-center text-3xl sm:text-5xl z-10 bg-black/30 px-2 py-2 backdrop-blur-md rounded-xl"
            >LASERGAME CENTER OKSBØL
            </h1>
        </div>
    </div>

    <div class="lg:w-300 mx-auto">
        <TheAktuelt />
        <TheVelkomst />
        <ThePriser />
        <TheReviews />
        <TheReglerForSpillet />
        <TheArrangementer />
        <TheFotokarrusel />
        <TheKontakt />
    </div>
</div>
</template>

<script setup>
const { t } = useI18n()
useSeoMeta({
  title: computed(() => t('seo.index.title')),
  description: computed(() => t('seo.index.description')),
})

// clear hashtag fra url når man klikker på nav links
const route = useRoute()

onMounted(() => {
  const section = route.query.scroll
  if (section) {
    history.replaceState(null, '', '/')
    setTimeout(() => {
      document.getElementById(section)?.scrollIntoView({ behavior: 'smooth' })
    }, 100)
  }
})
</script>
