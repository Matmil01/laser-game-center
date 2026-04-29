<template>
  <div id="kontakt" class="flex md:flex-row gap-8 p-8">
<!-- Kontakt os -->
<div class="flex-1 p-6 border-neon-neonred">
  <h2 class="text-white text-2xl font-bold text-center mb-8">Kontakt os</h2>
  <div class="mb-12.5 ml-22.5 flex text-xl items-center text-white">
    <img src="/icons/lokation.svg" alt="Adresse ikon" class="w-6 h-6 mr-4" />
    <span class="text-white text-center">{{ contact.address }}</span>
  </div>
  <div class="mb-12.5 ml-22.5 flex text-xl items-center text-white">
    <img src="/icons/telefon.svg" alt="Telefon ikon" class="w-6 h-6 mr-4" />
    <a v-if="contact.phone" :href="`tel:${contact.phone.replace(/\s/g, '')}`" class="hover:underline">{{ contact.phone }}</a>
  </div>
  <div class="ml-22.5 flex text-xl items-center text-white">
    <img src="/icons/mail.svg" alt="Mail ikon" class="w-6 h-6 mr-4" />
    <a v-if="contact.email" :href="`mailto:${contact.email}`" class="hover:underline">{{ contact.email }}</a>
  </div>
</div>
    <!-- Maps -->
<div class="flex-1 border-neon-neonred p-4 md:p-6 flex items-center justify-center mt-6 md:mt-0 box-border">      <iframe
        src="https://www.google.com/maps?q=Mønstervej+60,+6854+Henne&output=embed"
        class="w-full h-80 border-0"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>
