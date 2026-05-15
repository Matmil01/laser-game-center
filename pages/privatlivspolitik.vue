<template>
  <div class="bg-black min-h-screen">
    <div class="lg:w-300 mx-auto p-8">
      <div class="max-w-2xl mx-auto">

        <h1 class="text-4xl font-bold text-white mb-10">{{ $t('privatlivspolitik.title') }}</h1>

        <p class="text-white leading-relaxed mb-10">
          {{ $t('privatlivspolitik.intro') }}
        </p>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.dataansvarlig.title') }}</h2>
          <p class="text-white leading-relaxed">
            Lasergame Center Oksbøl<br>
            Mønstervej 60, 6854 Henne<br>
            CVR: {{ contact.cvr }}<br>
            Telefon: {{ contact.phone }}<br>
            E-mail: <a v-if="contact.email" :href="`mailto:${contact.email}`" class="underline hover:text-neonred transition-colors duration-300">{{ contact.email }}</a>
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.indsamler.title') }}</h2>
          <p class="text-white leading-relaxed mb-3">{{ $t('privatlivspolitik.indsamler.intro') }}</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li v-for="(item, i) in $tm('privatlivspolitik.indsamler.items')" :key="i">{{ $rt(item) }}</li>
          </ul>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.formaal.title') }}</h2>
          <p class="text-white leading-relaxed mb-3">{{ $t('privatlivspolitik.formaal.intro') }}</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li v-for="(item, i) in $tm('privatlivspolitik.formaal.items')" :key="i">{{ $rt(item) }}</li>
          </ul>
          <p class="text-white leading-relaxed mt-3">
            {{ $t('privatlivspolitik.formaal.retsgrundlag') }}
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.opbevaring.title') }}</h2>
          <p class="text-white leading-relaxed">
            {{ $t('privatlivspolitik.opbevaring.p1') }}<br>
            {{ $t('privatlivspolitik.opbevaring.p2') }}<br>
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.cookies.title') }}</h2>
          <p class="text-white leading-relaxed">
            {{ $t('privatlivspolitik.cookies.p1') }}<br>
            {{ $t('privatlivspolitik.cookies.p2') }}
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">{{ $t('privatlivspolitik.rettigheder.title') }}</h2>
          <p class="text-white leading-relaxed mb-3">{{ $t('privatlivspolitik.rettigheder.intro') }}</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li v-for="(item, i) in $tm('privatlivspolitik.rettigheder.items')" :key="i">{{ $rt(item) }}</li>
          </ul>
          <p class="text-white leading-relaxed mt-3">
            {{ $t('privatlivspolitik.rettigheder.contactPre') }}
            <a v-if="contact.email" :href="`mailto:${contact.email}`" class="underline hover:text-neonred transition-colors duration-300">{{ contact.email }}</a><span v-else>{{ $t('privatlivspolitik.rettigheder.contactFallback') }}</span>
            {{ $t('privatlivspolitik.rettigheder.contactPost') }}
          </p>
          <p class="text-white leading-relaxed mt-3">
            {{ $t('privatlivspolitik.rettigheder.datatilsynetPre') }}
            <a href="https://www.datatilsynet.dk" target="_blank" rel="noopener" class="underline hover:text-neonred transition-colors duration-300">{{ $t('privatlivspolitik.rettigheder.datatilsynetLink') }}</a>{{ $t('privatlivspolitik.rettigheder.datatilsynetPost') }}
          </p>
        </section>

      </div>
    </div>
  </div>
</template>

<script setup>
const { contact, fetchContactInfo } = useContactInfo()
onMounted(fetchContactInfo)
</script>
