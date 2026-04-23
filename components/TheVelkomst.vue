<template>
  <div class="flex gap-8 p-8 mb-7">
    <!-- Velkommen Box -->
    <div class="flex-1 border-2 border-neon-neonred p-6 relative">
      <h2 class="text-2xl mb-4 text-white">VELKOMMEN</h2>
      <p class="text-white pb-16">
       Kom og prøv en spændende aktivitet for dig og dine venner, hvor to hold dyster mod hinanden iført laservåben og veste i en gammel, forfalden bygning, hvor kun det stærkeste hold overlever.

Centeret befinder sig på Mønstervej 60, 6854 Henne og er indrettet som et mørkt labyrintområde på to etager fyldt med gemmesteder og overraskelser.

Max 5 personer per hold. Er I flere, laver vi turnering - og kun ét hold kan vinde æren.
Tør DU!?
      </p>
      <button class="neon-pulse-button absolute bottom-4 right-4 border-neon-neonred hover:border-neon-neongreen px-6 py-2 text-white transition-all">
        BOOK NU
      </button>
    </div>

    <!-- Episk Box -->
    <div class="flex-1 border-neon-neonred p-6">
      <h2 class="text-2xl mb-4 text-white">EPISK SEKTION</h2>
      <p class="text-white">
        MED LIGESÅ EPISK INDHOLD 
      </p>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
@keyframes neonPulse {
  0%, 100% {
    box-shadow: 0 0 18px 3px #00FF00;
  }
  50% {
    box-shadow: 0 0 30px 8px #00FF00;
  }
}

.neon-pulse-button:hover {
  animation: neonPulse 1.5s ease-in-out infinite;
}
</style>
