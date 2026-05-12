<template>
  <div id="priser" class="scroll-mt-24">
    <h2 class="text-4xl font-bold text-white text-center">{{ $t('priser.title') }}</h2>
  <div class="sm:flex gap-8 p-8 mb-7">
    <!-- Card 1 - Green -->
    <div class="flex-1 border-neon-neongreen p-8 flex flex-col mb-6 md:mb-0">
      <h2 class="text-2xl text-white text-center mb-6">1. {{ $t('priser.game') }}</h2>
      <div class="border-b-2 border-neongreen mb-8" style="box-shadow: 0 0 18px 3px #00FF00;"></div>

      <div class="flex-grow">
        <p class="text-white text-center text-xl mb-4">{{ $t('priser.price1') }}</p>
        <p class="text-white text-center">20 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 2 - Pink -->
    <div class="flex-1 border-neon-neonpink p-8 flex flex-col mb-6 md:mb-0">
      <h2 class="text-2xl text-white text-center mb-6">2. {{ $t('priser.game') }}</h2>
      <div class="border-b-2 border-neonpink mb-8" style="box-shadow: 0 0 18px 3px #FF009D;"></div>

      <div class="flex-grow">
        <p class="text-white text-center text-xl mb-4">{{ $t('priser.price2') }}</p>
        <p class="text-white text-center">40 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 3 - Orange -->
    <div class="flex-1 border-neon-neonorange p-8 flex flex-col mb-6 md:mb-0">
      <h2 class="text-2xl text-white text-center mb-6">3. {{ $t('priser.game') }}</h2>
      <div class="border-b-2 border-neonorange mb-8" style="box-shadow: 0 0 18px 3px #FF9D00;"></div>

      <div class="flex-grow">
        <p class="text-white text-center text-xl mb-4">{{ $t('priser.price3') }}</p>
        <p class="text-white text-center">60 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>

    <!-- Card 4 - Blue -->
    <div class="flex-1 border-neon-neonblue p-8 flex flex-col mb-6 md:mb-0">
      <h2 class="text-2xl text-white text-center mb-6">4. {{ $t('priser.game') }}</h2>
      <div class="border-b-2 border-neonblue mb-8" style="box-shadow: 0 0 18px 3px #00C3FF;"></div>

      <div class="flex-grow">
        <p class="text-white text-center text-xl mb-4">{{ $t('priser.price4') }}</p>
        <p class="text-white text-center">80 {{ $t('common.minutes') }}</p>
      </div>

      <p class="text-white text-xs text-center mt-8 opacity-70">{{ $t('priser.perPerson') }}<br>{{ $t('priser.includes') }}</p>
    </div>
  </div>
  </div>
</template>