<template>
<div class="bg-black sticky top-0 z-50">
  <nav class="px-6 py-4 max-w-300 mx-auto flex items-center justify-between">
<h1 class="flex items-center gap-2">
<a href="/" @mouseenter="hovered = true" @mouseleave="hovered = false">
  <img
    :src="hovered ? '/icons/logoGif.gif' : '/icons/logoHvid.svg'"
    alt="Logo"
    class="h-8.75 sm:h-11.25 inline-block"
  />
</a>
</h1>

    <div class="flex items-center gap-6">
      <a href="/#priser" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">Priser</a>
      <a href="/#regler" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">Regler</a>
      <a href="/#arrangementer" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">Arrangementer</a>
      <a href="/#kontakt" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">Kontakt</a>
      <NeonButton to="/booking" text="BOOKING" custom-class="!px-4 !py-1 text-sm" />
    </div>
  </nav>
</div>
</template>

<script setup>
import { ref } from 'vue'
const hovered = ref(false)
</script>