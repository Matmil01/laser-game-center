<template>
  <div id="regler" class="md:flex gap-8 p-8 mb-7 scroll-mt-24">
    <!-- Left Box - Rules Text -->
    <div class="flex-1 border-neon-neonred p-6 mb-6 md:mb-0">
      <h2 class="text-2xl mb-4 text-white">{{ $t('regler.title') }}</h2>
      <p class="text-white">
        {{ $t('regler.p1') }}<br><br>
        {{ $t('regler.p2') }}<br><br>
        {{ $t('regler.p3') }}<br><br>
        {{ $t('regler.p4') }}<br><br>
        {{ $t('regler.p5') }}<br><br>
      </p>
    </div>

    <!-- Right Box - YouTube Video -->
    <div class="flex-1 border-neon-neonred">
      <div class="aspect-video w-full h-full">
          <video
              src="/videos/lasergamecenteroksboelwalkthrough.mp4"
              style="width: 100%; height: 100%; object-fit: cover;"
              controls
          ></video>
      </div>
    </div>
  </div>
</template>

<script>
</script>
