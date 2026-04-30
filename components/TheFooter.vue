<template>
<div class="bg-black pb-2">
    <footer class="px-4 py-4 w-full max-w-2xl mx-auto text-center text-white text-sm opacity-70">
        © {{ new Date().getFullYear() }} Laser Game Center | CVR {{ contact.cvr }} | {{ contact.phone }} | {{ contact.address }}
    </footer>
    <div class="flex justify-center gap-4 mb-4">
        <a href="#" class="text-white hover:text-neonred transition-colors duration-300">Facebook</a>
        <a v-if="contact.email" :href="`mailto:${contact.email}`" class="text-white hover:text-neonred transition-colors duration-300">{{ contact.email }}</a>
    </div>
</div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>