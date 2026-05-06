<template>
  <div class="flex-1 md:flex gap-8 p-8 mb-7">
    <!-- Velkommen Box -->
    <div class="border-2 border-neon-neonred p-6 relative md:w-6/12 mb-6 md:mb-0">
      <h2 class="text-2xl text-white mb-1">{{ $t('velkomst.title') }}</h2>
      <p class="text-white md:mb-2 mb-6">
        {{ $t('velkomst.p1') }}<br><br>
        {{ $t('velkomst.p2') }}<br><br>
        {{ $t('velkomst.p3') }}<br>
        {{ $t('velkomst.p4') }}
      </p>
      <NeonButton to="/booking" custom-class="absolute bottom-4 right-4" :text="$t('common.bookNow')" />
    </div>

    <!-- Episk Box -->
    <div class="border-neon-neonred md:w-6/12">
        <video
            src="/videos/hero.mp4"
            style="width: 100%; height: 100%; object-fit: cover;"
            autoplay
            muted
            loop
            playsinline
            preload="none"
        ></video>
    </div>
  </div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>
