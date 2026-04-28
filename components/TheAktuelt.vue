<template>
	<div
		v-if="contact && contact.aktuelt_text && contact.aktuelt_visible !== '0'"
		class="relative max-w-md w-full mx-auto mt-14 mb-4 px-6 py-5 bg-black text-white border-2"
		:style="{
			borderColor: contact.aktuelt_color || '#FF9D00',
			boxShadow: `0 0 18px 2px ${contact.aktuelt_color || '#FF9D00'}`
		}"
	>
		<div class="flex items-center gap-4">
			<img v-if="contact.aktuelt_icon && ['icon1', 'icon2'].includes(contact.aktuelt_icon)" :src="`/icons/${contact.aktuelt_icon}.svg`" class="w-16 h-16 shrink-0 object-contain select-none" alt="" />
			<div>
				<h2 class="text-lg font-black tracking-wide mb-1">{{ contact.aktuelt_title || 'AKTUELT' }}</h2>
				<p class="text-sm leading-relaxed">{{ contact.aktuelt_text }}</p>
			</div>
		</div>
	</div>
</template>

<script setup>
// useContactInfo fetcher også "aktuelt" fields fra settings.php
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>