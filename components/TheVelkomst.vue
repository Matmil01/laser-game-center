<template>
  <div class="flex-1 md:flex gap-8 p-8 mb-7">
    <!-- Velkommen Box -->
    <div class="border-2 border-neon-neonred p-6 relative md:w-6/12 mb-6 md:mb-0">
      <h2 class="text-2xl text-white mb-1">VELKOMMEN</h2>
      <p class="text-white md:mb-2 mb-6">
       Kom og prøv en spændende aktivitet for dig og dine venner – en af de fedeste oplevelser i Vestjylland! Her dyster to hold mod hinanden iført laservåben og veste i en gammel, forfalden bygning, hvor kun det stærkeste hold overlever <br><br>

Er du på udkig efter sjove aktiviteter nær Blåvand, er dette den perfekte oplevelse for både venner, familie og kollegaer. Centeret befinder sig på Mønstervej 60, 6854 Henne og er indrettet som et mørkt labyrintområde på to etager fyldt med gemmesteder og overraskelser. <br><br>

Max 5 personer per hold. Er I flere, laver vi turnering - og kun ét hold kan vinde æren. <br>
Tør DU!?
      </p>
      <NeonButton to="/booking" custom-class="absolute bottom-4 right-4" text="BOOK NU" />
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
