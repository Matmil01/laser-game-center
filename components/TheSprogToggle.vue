<template>
  <div class="flex items-center gap-1">
    <button
      v-for="option in otherLangs"
      :key="option.code"
      @click="setLocale(option.code)"
      :title="option.label"
      class="text-xl leading-none cursor-pointer"
    >
      {{ option.flag }}
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const { locale, setLocale } = useI18n()

const langs = [
  { code: 'da', flag: '🇩🇰', label: 'Dansk' },
  { code: 'en', flag: '🇬🇧', label: 'English' },
  { code: 'de', flag: '🇩🇪', label: 'Deutsch' },
]

const otherLangs = computed(() => langs.filter(l => l.code !== locale.value))
</script>
