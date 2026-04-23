<template>
  <div class="flex flex-col md:flex-row gap-8 p-8">
    <!-- Kontakt os -->
    <div class="flex-1 p-6 border-neon-neonred">
      <h2 class="text-white text-xl font-bold mb-4">Kontakt os</h2>
        <p class="mb-2 text-white"><span class="font-semibold">Adresse:</span> Mønstervej 60 6854 Henne</p>
        <p class="mb-2 text-white"><span class="font-semibold">Telefon:</span> <a href="tel:+4512345678" class=" hover:underline">+45 29 46 56 75 </a></p>
        <p class="text-white"><span class="font-semibold">Email:</span> <a href="mailto:lasergamecenter@gmail.com" class=" hover:underline">lasergamecenter@gmail.com</a></p>
    </div>
    <!-- Maps -->
    <div class="flex-1 border-neon-neonred p-6 flex items-center justify-center">
      <iframe
        src="https://www.google.com/maps?q=Mønstervej+60,+6854+Henne&output=embed"
        class="w-full h-64 border-0"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</template>

<script>
</script>
