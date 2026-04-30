<template>
<div class="bg-black sticky top-0 z-50">
  <nav class="px-6 py-4 w-300 mx-auto flex items-center justify-between">
<h1 class="flex items-center gap-2">
  <NuxtLink to="/"><img src="/icons/LogoHvid.svg" alt="Logo" class="h-15 inline-block" /></NuxtLink>
</h1> 
    <div class="flex items-center gap-6">
      <a href="#priser" class="text-white text-sm hover:text-neonred transition-colors duration-300">Priser</a>
      <a href="#regler" class="text-white text-sm hover:text-neonred transition-colors duration-300">Regler</a>
      <a href="#arrangementer" class="text-white text-sm hover:text-neonred transition-colors duration-300">Arrangementer</a>
      <a href="#kontakt" class="text-white text-sm hover:text-neonred transition-colors duration-300">Kontakt</a>
      <NeonButton to="/booking" text="BOOKING" custom-class="!px-4 !py-1 text-sm" />
    </div>
  </nav>
</div>
</template>
