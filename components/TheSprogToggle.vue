<template>
  <div class="flex items-center gap-1">
    <button
      v-for="option in otherLangs"
      :key="option.flag"
      @click="setLocale(option.flag)"
      :title="option.label"
      class="leading-none cursor-pointer"
    >
      <img :src="`/icons/${option.flag}.svg`" :alt="option.label" class="w-6 h-4 object-cover" />
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const { locale, setLocale } = useI18n()

const langs = [
  { flag: 'da', label: 'Dansk' },
  { flag: 'en', label: 'English' },
  { flag: 'de', label: 'Deutsch' },
]

const otherLangs = computed(() => langs.filter(l => l.flag !== locale.value))
</script>
