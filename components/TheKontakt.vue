<template>
  <div class="flex flex-col md:flex-row gap-8 p-8">
    <!-- Kontakt os -->
    <div class="flex-1 p-6 border-neon-neonred box-border">
      <h2 class="text-white text-2xl font-bold mb-4">Kontakt os</h2>
      <div class="mb-2 flex items-center text-white">
        <img src="/icons/lokation.svg" alt="Adresse ikon" class="w-6 h-6 mr-4" />
        <span class="text-white">Mønstervej 60 6854 Henne</span>
      </div>
      <div class="mb-2 flex items-center text-white">
        <img src="/icons/telefon.svg" alt="Telefon ikon" class="w-6 h-6 mr-4" />
        <a href="tel:+4512345678" class="hover:underline">+45 29 46 56 75</a>
      </div>
      <div class="flex items-center text-white">
        <img src="/icons/mail.svg" alt="Mail ikon" class="w-6 h-6 mr-4" />
        <a href="mailto:lasergamecenter@gmail.com" class="hover:underline">lasergamecenter@gmail.com</a>
      </div>
    </div>
    <!-- Maps -->
    <div class="flex-1 border-neon-neonred flex items-center justify-center">
      <iframe
        src="https://www.google.com/maps?q=Mønstervej+60,+6854+Henne&output=embed"
        class="w-full h-80 border-0"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</template>

<script>
</script>
