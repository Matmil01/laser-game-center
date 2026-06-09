<template>
  <div id="priser" class="scroll-mt-24">
    <h2 class="text-2xl sm:text-4xl font-bold text-white text-center">{{ $t('priser.title') }}</h2>
  <div class="sm:flex gap-8 p-8 mb-7">
    <!-- Card 1 - Green -->
    <div class="flex-1 border-neon-neongreen p-8 flex flex-col mb-6 md:mb-0 group h-80 transition-transform duration-500 hover:scale-105">
      <h2 class="text-2xl text-white text-center mb-6">1 {{ $t('priser.game') }}</h2>
      <NeonButton to="/booking" customClass="mb-5 !border-neon-neongreen transition-all duration-500 !p-0 !text-[0px] group-hover:!py-2 group-hover:!px-6 group-hover:!text-base hover:!border-neon-neonred" :text="$t('common.bookNow')" />


      <div class="grow">
        <p class="text-white text-center text-xl mb-4">{{ formatted(1, locale) }}</p>
        <p class="text-white text-center">20-25 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 2 - Pink -->
    <div class="flex-1 border-neon-neonpink p-8 flex flex-col mb-6 md:mb-0 group h-80 transition-transform duration-500 hover:scale-105">
      <h2 class="text-2xl text-white text-center mb-6">2 {{ $t('priser.games') }}</h2>
      <NeonButton to="/booking" customClass="mb-5 !border-neon-neonpink transition-all duration-500 !p-0 !text-[0px] group-hover:!py-2 group-hover:!px-6 group-hover:!text-base hover:!border-neon-neonred" :text="$t('common.bookNow')" />

      <div class="grow">
        <p class="text-white text-center text-xl mb-4">{{ formatted(2, locale) }}</p>
        <p class="text-white text-center">40-45 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 3 - Orange -->
    <div class="flex-1 border-neon-neonorange p-8 flex flex-col mb-6 md:mb-0 group h-80 transition-transform duration-500 hover:scale-105">
      <h2 class="text-2xl text-white text-center mb-6">3 {{ $t('priser.games') }}</h2>
      <NeonButton to="/booking" customClass="mb-5 !border-neon-neonorange transition-all duration-500 !p-0 !text-[0px] group-hover:!py-2 group-hover:!px-6 group-hover:!text-base hover:!border-neon-neonred" :text="$t('common.bookNow')" />

      <div class="grow">
        <p class="text-white text-center text-xl mb-4">{{ formatted(3, locale) }}</p>
        <p class="text-white text-center">60-65 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 4 - Blue -->
    <div class="flex-1 border-neon-neonblue p-8 flex flex-col mb-6 md:mb-0 group h-80 transition-transform duration-500 hover:scale-105">
      <h2 class="text-2xl text-white text-center mb-6">4 {{ $t('priser.games') }}</h2>
      <NeonButton to="/booking" customClass="mb-5 !border-neon-neonblue transition-all duration-500 !p-0 !text-[0px] group-hover:!py-2 group-hover:!px-6 group-hover:!text-base hover:!border-neon-neonred" :text="$t('common.bookNow')" />

      <div class="grow">
        <p class="text-white text-center text-xl mb-4">{{ formatted(4, locale) }}</p>
        <p class="text-white text-center">80-85 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>
  </div>
  </div>
</template>

<script setup>
import { usePrices } from '~/composables/usePrices.js'

const { locale } = useI18n()
const { formatted } = usePrices()
const { fetchSettings } = useSettings()
onMounted(fetchSettings)
</script>
