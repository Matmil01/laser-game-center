<template>
  <div id="arrangementer" class="scroll-mt-24">
  <h2 class="sm:text-4xl text-2xl font-bold text-white text-center">{{ $t('arrangementer.title') }}</h2>
  <div class="flex gap-8 items-center justify-start md:justify-center relative my-7 px-10 sm:px-20 overflow-hidden">
    <!-- Venstre pil -->
    <button
      @click="slideLeft"
      class="z-20 absolute left-2 md:left-0 top-1/2 -translate-y-1/2 p-2"
      :aria-label="$t('arrangementer.prev')"
    >
      <img src="/icons/pil.svg" :alt="$t('arrangementer.prev')" class="sm:w-10 sm:h-10 w-6 h-6 rotate-180 cursor-pointer transition" />
    </button>

    <!-- Billedboks (skjules på mobil) -->
    <transition
      :enter-active-class="direction === 'left'
        ? 'transition-all duration-500 ease-in-out'
        : 'transition-all duration-500 ease-in-out'"
      :leave-active-class="direction === 'left'
        ? 'transition-all duration-500 ease-in-out'
        : 'transition-all duration-500 ease-in-out'"
      :enter-from-class="direction === 'left'
        ? '-translate-x-full opacity-0'
        : 'translate-x-full opacity-0'"
      enter-to-class="translate-x-0 opacity-100"
      leave-from-class="translate-x-0 opacity-100"
      :leave-to-class="direction === 'left'
        ? 'translate-x-full opacity-0'
        : '-translate-x-full opacity-0'"
      mode="out-in"
    >
      <div
        class="hidden md:flex flex-1 max-w-lg max-h-100 justify-center overflow-hidden"
        :key="slides[current].image"
      >
        <img
          :src="slides[current].image"
          :alt="slides[current].alt"
          class="object-cover w-full h-full"
        />
      </div>
    </transition>

    <!-- Tekstboks -->
    <transition
      :enter-active-class="direction === 'left'
        ? 'transition-all duration-500 ease-in-out'
        : 'transition-all duration-500 ease-in-out'"
      :leave-active-class="direction === 'left'
        ? 'transition-all duration-500 ease-in-out'
        : 'transition-all duration-500 ease-in-out'"
      :enter-from-class="direction === 'left'
        ? '-translate-x-full opacity-0'
        : 'translate-x-full opacity-0'"
      enter-to-class="translate-x-0 opacity-100"
      leave-from-class="translate-x-0 opacity-100"
      :leave-to-class="direction === 'left'
        ? 'translate-x-full opacity-0'
        : '-translate-x-full opacity-0'"
      mode="out-in"
      @after-enter="onTextAfterEnter"
    >
      <div
        ref="textBoxRef"
        class="flex-1 border-neon-neonred p-6 flex flex-col justify-start md:justify-center"
        :key="slides[current].title"
        :style="minTextHeight ? { minHeight: minTextHeight + 'px' } : {}"
      >
        <h2 class="text-xl sm:text-2xl font-bold mb-4 text-white">{{ slides[current].title }}</h2>
        <div class="text-white flex flex-col space-y-6">
          <span v-for="(line, i) in slides[current].text.split('\n')" :key="i">{{ line }}</span>
        </div>
      </div>
    </transition>

    <!-- Højre pil -->
    <button
      @click="slideRight"
      class="z-20 absolute right-2 md:right-0 top-1/2 -translate-y-1/2 p-2"
      :aria-label="$t('arrangementer.next')"
    >
      <img src="/icons/pil.svg" :alt="$t('arrangementer.next')" class="sm:w-10 sm:h-10 w-6 h-6 cursor-pointer transition" />
    </button>
  </div>
  </div>
</template>

<script setup>
const { t } = useI18n()

const current = ref(0)
const direction = ref('right')
// Keeps track of the tallest slide so the section doesn't jump in height when switching between slides
const textBoxRef = ref(null)
const minTextHeight = ref(0)

onMounted(async () => {
  await nextTick()
  if (textBoxRef.value) {
    minTextHeight.value = textBoxRef.value.offsetHeight
  }
})

function onTextAfterEnter(el) {
  const h = el.offsetHeight
  if (h > minTextHeight.value) minTextHeight.value = h
}

const slides = computed(() => [
  {
    image: '/photos/lasergame-spil-oksboel-10.webp',
    alt:   t('arrangementer.slides.0.alt'),
    title: t('arrangementer.slides.0.title'),
    text:  t('arrangementer.slides.0.text'),
  },
  {
    image: '/photos/lasergame-spil-oksboel-11.webp',
    alt:   t('arrangementer.slides.1.alt'),
    title: t('arrangementer.slides.1.title'),
    text:  t('arrangementer.slides.1.text'),
  },
  {
    image: '/photos/lasergame-spil-oksboel-4.webp',
    alt:   t('arrangementer.slides.2.alt'),
    title: t('arrangementer.slides.2.title'),
    text:  t('arrangementer.slides.2.text'),
  },
  {
    image: '/photos/lasergame-spil-oksboel-16.webp',
    alt:   t('arrangementer.slides.3.alt'),
    title: t('arrangementer.slides.3.title'),
    text:  t('arrangementer.slides.3.text'),
  },
  {
    image: '/photos/lasergame-spil-oksboel-1.webp',
    alt:   t('arrangementer.slides.4.alt'),
    title: t('arrangementer.slides.4.title'),
    text:  t('arrangementer.slides.4.text'),
  },
])

const totalSlides = 5

function slideRight() {
  direction.value = 'right'
  current.value = (current.value + 1) % totalSlides
}

function slideLeft() {
  direction.value = 'left'
  current.value = (current.value - 1 + totalSlides) % totalSlides
}
</script>