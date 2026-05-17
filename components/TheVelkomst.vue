<template>
  <div class="flex-1 md:flex gap-8 p-8 mb-7">
    <!-- Velkommen Box -->
    <div class="border-2 border-neon-neonred p-6 relative md:w-6/12 mb-6 md:mb-0">
      <h2 class="text-2xl text-white mb-4">
        <!-- We now translate the prefix using i18n -->
        {{ $t('velkomst.title_prefix') }} <br /><span class="text-neon-neonred" ref="textRef"></span>
      </h2>
      <p class="text-white mb-4">
        {{ $t('velkomst.p1') }}<br><br>
        {{ $t('velkomst.p2') }}<br><br>
        {{ $t('velkomst.p3') }}<br>
        {{ $t('velkomst.p4') }}
      </p>
      <div class="flex justify-end">
        <NeonButton to="/booking" custom-class="md:absolute md:bottom-4 md:right-4" :text="$t('common.bookNow')" />
      </div>
    </div>

    <!-- Episk Box -->
    <div class="border-neon-neonred md:w-6/12">
            <video
                src="/videos/lasergamecenteroksboeltrailer.mp4"
                poster="/photos/lasergame-spil-oksboel-1.webp"
                style="width: 100%; height: 100%; object-fit: cover;"
                controls
            ></video>
        </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import Typed from 'typed.js'

const { settings, fetchSettings } = useSettings()
const { t, locale } = useI18n()

const textRef = ref(null)
let typed = null

const initTyped = () => {
  if (typed) {
    typed.destroy()
  }

  const translatedWords = [
    t('velkomst.word_1').toString(),
    t('velkomst.word_2').toString(),
    t('velkomst.word_3').toString(),
    t('velkomst.word_4').toString(),
    t('velkomst.word_5').toString(),
    t('velkomst.word_6').toString()
  ]

  typed = new Typed(textRef.value, {
    strings: translatedWords,
    typeSpeed: 80,
    backSpeed: 40,
    backDelay: 2000,
    loop: true,
    showCursor: true,
    cursorChar: '|'
  })
}

onMounted(() => {
  fetchSettings()
  initTyped()
})

watch(locale, () => {
  initTyped()
})

onUnmounted(() => {
  if (typed) {
    typed.destroy()
  }
})
</script>
