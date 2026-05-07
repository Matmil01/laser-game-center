<template>
<div class="bg-black sticky top-0 z-50">
  <nav class="px-6 py-4 max-w-300 mx-auto flex items-center justify-between">
<h1 class="flex items-center gap-2">
<a href="/" @click.prevent="handleLogoClick"><img src="/icons/logoHvid.svg" alt="Logo" class="h-10 sm:h-15 inline-block" /></a>
</h1>

    <div class="flex items-center gap-6">
      <a href="/#priser" @click.prevent="handleSectionNav('priser')" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.priser') }}</a>
      <a href="/#regler" @click.prevent="handleSectionNav('regler')" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.regler') }}</a>
      <a href="/#arrangementer" @click.prevent="handleSectionNav('arrangementer')" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.arrangementer') }}</a>
      <a href="/#kontakt" @click.prevent="handleSectionNav('kontakt')" class="text-white text-sm hover:text-neonred transition-colors duration-300 hidden md:block">{{ $t('nav.kontakt') }}</a>
      <NeonButton to="/booking" :text="$t('nav.booking')" custom-class="!px-4 !py-1 text-sm" />
      <TheSprogToggle />
    </div>
  </nav>
</div>
</template>

<script setup>
// clear hashtag fra url når man klikker på nav links
const route = useRoute()

function handleLogoClick() {
  if (route.path === '/') {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  } else {
    navigateTo('/')
  }
}

function handleSectionNav(section) {
  if (route.path === '/') {
    document.getElementById(section)?.scrollIntoView({ behavior: 'smooth' })
    history.replaceState(null, '', '/')
  } else {
    navigateTo(`/?scroll=${section}`)
  }
}
</script>