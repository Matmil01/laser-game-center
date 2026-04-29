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
			<!-- mask-image trick. No need to inline the SVG markup. -->
			<div
				v-if="contact.aktuelt_icon"
				class="w-16 h-16 shrink-0 select-none"
				:style="{
					backgroundColor: contact.aktuelt_color || '#FF9D00',
					maskImage: `url('/icons/${contact.aktuelt_icon}.svg')`,
					WebkitMaskImage: `url('/icons/${contact.aktuelt_icon}.svg')`,
					maskSize: 'contain',
					WebkitMaskSize: 'contain',
					maskRepeat: 'no-repeat',
					WebkitMaskRepeat: 'no-repeat',
					maskPosition: 'center',
					WebkitMaskPosition: 'center',
				}"
			/>
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