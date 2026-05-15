<template>
<div class="bg-black pb-2">
    <footer class="px-4 py-4 w-full max-w-2xl mx-auto text-center text-white text-sm opacity-70">
        © {{ new Date().getFullYear() }} Lasergame Center | CVR {{ contact.cvr }} | {{ contact.phone }} | Mønstervej 60, 6854 Henne
    </footer>
    <div class="flex flex-wrap justify-center gap-4 mb-4 px-4">
        <a href="https://www.facebook.com/lasergamecenter/" target="_blank" rel="noopener" class="text-white hover:text-neonred transition-colors duration-300">Facebook</a>
        <a v-if="contact.email" :href="`mailto:${contact.email}`" class="text-white hover:text-neonred transition-colors duration-300 break-all min-w-0">{{ contact.email }}</a>
        <NuxtLink to="/privatlivspolitik" class="text-white hover:text-neonred transition-colors duration-300">{{ $t('privatlivspolitik.title') }}</NuxtLink>
    </div>
</div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>