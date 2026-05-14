<template>
	<div
		v-if="contact && aktueltText && contact.aktuelt_visible !== '0'"
		class="relative max-w-md md:w-full mx-8 sm:mx-auto mt-14 mb-4 px-6 py-5 bg-black text-white border-2"
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
				<h2 class="text-lg font-black tracking-wide mb-1">{{ aktueltTitle }}</h2>
				<p class="text-sm leading-relaxed whitespace-pre-wrap">{{ aktueltText }}</p>
			</div>
		</div>
	</div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
const { locale } = useI18n()

const aktueltTitle = computed(() => {
  if (locale.value !== 'da') {
    const t = contact.value[`aktuelt_title_${locale.value}`]
    if (t) return t
  }
  return contact.value.aktuelt_title || 'AKTUELT'
})

const aktueltText = computed(() => {
  if (locale.value !== 'da') {
    const t = contact.value[`aktuelt_text_${locale.value}`]
    if (t) return t
  }
  return contact.value.aktuelt_text || ''
})

onMounted(fetchContactInfo)
</script>
