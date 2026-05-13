<template>
  <div class="bg-black min-h-screen">
    <div class="lg:w-300 mx-auto p-8">
      <div class="max-w-2xl mx-auto">

        <h1 class="text-4xl font-bold text-white mb-10">Privatlivspolitik</h1>

        <p class="text-white leading-relaxed mb-10">
          Vi behandler personoplysninger, når du foretager en booking via vores hjemmeside.
        </p>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Dataansvarlig</h2>
          <p class="text-white leading-relaxed">
            Laser Game Center Oksbøl<br>
            Mønstervej 60, 6854 Henne<br>
            CVR: {{ contact.cvr }}<br>
            Telefon: {{ contact.phone }}<br>
            E-mail: <a v-if="contact.email" :href="`mailto:${contact.email}`" class="underline hover:text-neonred transition-colors duration-300">{{ contact.email }}</a>
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Hvilke oplysninger vi indsamler</h2>
          <p class="text-white leading-relaxed mb-3">Når du opretter en booking, registrerer vi:</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li>Dit navn</li>
            <li>Din e-mailadresse</li>
            <li>Dit telefonnummer</li>
            <li>Eventuel besked/note</li>
            <li>Bookingdetaljer (dato, tidspunkt, antal spil, antal deltagere)</li>
          </ul>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Formål og retsgrundlag</h2>
          <p class="text-white leading-relaxed mb-3">Oplysningerne bruges udelukkende til:</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li>at oprette og bekræfte din booking</li>
            <li>at sende dig en bekræftelsesmail</li>
            <li>at kontakte dig vedrørende din reservation</li>
          </ul>
          <p class="text-white leading-relaxed mt-3">
            Retsgrundlaget for behandlingen er GDPR artikel 6(1)(b) — behandling er nødvendig for at opfylde en aftale med dig.
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Opbevaring af data</h2>
          <p class="text-white leading-relaxed">
            Dine oplysninger opbevares sikkert hos Simply.com på europæiske servere.<br>
            Vi deler ikke dine oplysninger med andre.<br>
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Cookies</h2>
          <p class="text-white leading-relaxed">
            Vi anvender ingen cookies til statistik eller markedsføring.<br>
            Hjemmesiden bruger ikke tracking eller analyseredskaber.
          </p>
        </section>

        <section class="mb-8">
          <h2 class="text-2xl text-white font-bold mb-3">Dine rettigheder</h2>
          <p class="text-white leading-relaxed mb-3">Du har ret til:</p>
          <ul class="list-disc list-inside text-white space-y-1 leading-relaxed">
            <li>indsigt i de oplysninger vi har om dig</li>
            <li>at få rettet ukorrekte oplysninger</li>
            <li>at få slettet dine oplysninger</li>
            <li>at få begrænset behandlingen af dine oplysninger</li>
            <li>dataportabilitet (at modtage dine oplysninger i et maskinlæsbart format)</li>
            <li>at gøre indsigelse mod behandlingen</li>
          </ul>
          <p class="text-white leading-relaxed mt-3">
            Kontakt os på
            <a v-if="contact.email" :href="`mailto:${contact.email}`" class="underline hover:text-neonred transition-colors duration-300">{{ contact.email }}</a><span v-else>vores e-mailadresse</span>
            for at udøve dine rettigheder.
          </p>
          <p class="text-white leading-relaxed mt-3">
            Du kan også klage til
            <a href="https://www.datatilsynet.dk" target="_blank" rel="noopener" class="underline hover:text-neonred transition-colors duration-300">Datatilsynet</a>,
            hvis du mener, vi ikke behandler dine oplysninger korrekt.
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
