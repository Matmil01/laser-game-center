<template>
<div class="bg-black sticky top-0 z-50">
  <nav class="px-6 py-4 max-w-300 mx-auto flex items-center justify-between">
<h1 class="flex items-center gap-2">
<a href="/"><img src="/icons/logoHvid.svg" alt="Logo" class="h-10 sm:h-15 inline-block" /></a>
</h1>

    <div class="flex items-center gap-6">
      <a href="/#priser" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.priser') }}</a>
      <a href="/#regler" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.regler') }}</a>
      <a href="/#arrangementer" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.arrangementer') }}</a>
      <a href="/#kontakt" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.kontakt') }}</a>
      <NeonButton to="/booking" :text="$t('nav.booking')" custom-class="!px-4 !py-1 text-sm" />
      <TheSprogToggle />
    </div>
  </nav>
</div>
</template>