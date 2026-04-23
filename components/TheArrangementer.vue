<template>
  <h2 class="md:text-4xl font-bold text-white text-center">arrangementer</h2>
  <div class="flex gap-8 p-8 items-center justify-start md:justify-center relative mb-7">
    <!-- Venstre pil -->
    <button
      @click="slideLeft"
      class="z-20 absolute left-2 md:left-0 top-1/2 -translate-y-1/2 p-2"
      aria-label="Forrige"
    >
      <img src="/icons/pil.svg" alt="Forrige" class="w-10 h-10 rotate-180 cursor-pointer transition" />
    </button>

    <!-- Billedboks (skjules på mobil) -->
    <transition
      :enter-active-class="direction === 'left' 
        ? 'transition-all duration-500 ease-in-out' 
        : 'transition-all duration-500 ease-in-out'"
      :leave-active-class="direction === 'left' 
        ? 'transition-all duration-500 ease-in-out' 
        : 'transition-all duration-500 ease-in-out'"
      :enter-from-class="direction === 'left' 
        ? '-translate-x-full opacity-0' 
        : 'translate-x-full opacity-0'"
      enter-to-class="translate-x-0 opacity-100"
      leave-from-class="translate-x-0 opacity-100"
      :leave-to-class="direction === 'left' 
        ? 'translate-x-full opacity-0' 
        : '-translate-x-full opacity-0'"
      mode="out-in"
    >
      <div
        class="hidden md:flex flex-1 max-w-lg max-h-100 min-h-75 min-w-75 justify-center overflow-hidden"
        :key="slides[current].image"
      >
        <img
          :src="slides[current].image"
          :alt="slides[current].alt"
          class="object-cover w-full h-full"
        />
      </div>
    </transition>

    <!-- Tekstboks -->
    <transition
      :enter-active-class="direction === 'left' 
        ? 'transition-all duration-500 ease-in-out' 
        : 'transition-all duration-500 ease-in-out'"
      :leave-active-class="direction === 'left' 
        ? 'transition-all duration-500 ease-in-out' 
        : 'transition-all duration-500 ease-in-out'"
      :enter-from-class="direction === 'left' 
        ? '-translate-x-full opacity-0' 
        : 'translate-x-full opacity-0'"
      enter-to-class="translate-x-0 opacity-100"
      leave-from-class="translate-x-0 opacity-100"
      :leave-to-class="direction === 'left' 
        ? 'translate-x-full opacity-0' 
        : '-translate-x-full opacity-0'"
      mode="out-in"
    >
      <div
        class="flex-1 max-w-lg max-h-100 min-h-75 min-w-75 border-neon-neonred p-6 flex flex-col justify-start md:justify-center mx-12 md:mx-0"
        :key="slides[current].title"
      >
        <h2 class="text-2xl font-bold mb-4 text-white">{{ slides[current].title }}</h2>
        <div class="text-white flex flex-col space-y-6">
          <span v-for="(line, i) in slides[current].text.split('\n')" :key="i">{{ line }}</span>
        </div>
      </div>
    </transition>

    <!-- Højre pil -->
    <button
      @click="slideRight"
      class="z-20 absolute right-2 md:right-0 top-1/2 -translate-y-1/2 p-2"
      aria-label="Næste"
    >
      <img src="/icons/pil.svg" alt="Næste" class="w-10 h-10 cursor-pointer transition" />
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      current: 0,
      direction: 'right',
      slides: [
        {
          image: "/photos/stag-prank-bachelor-party.jpg",
          alt: "Arrangement 1",
          title: "Fødselsdag",
          text: "Gør fødselsdagen ekstra spændende med lasergame!\nVed børnefødselsdage spiller fødselaren gratis ved opfyldelse af minimumskrav, hvilket skaber en særlig oplevelse og en dag, børnene sent vil glemme.\nDette kræver min. booking af to spil for mindst 6 personer! Gratis fødselsar er min. 8 personer",
        },
        {
          image: "/photos/race-planet-lasergame.jpg",
          alt: "Arrangement 2",
          title: "Team Building",
          text: "Gennem intense lasergame-oplevelser styrkes relationer og fællesskab på en sjov og anderledes måde.\nDet er en oplagt aktivitet for virksomheder, foreninger eller grupper.",
        },
        {
          image: "/photos/F for får.png",
          alt: "Arrangement 3",
          title: "Selskaber og private arrangementer",
          text: "Uanset om det er en vennegruppe, familieevent eller anden fejring, kan centeret bookes uden for højsæsonen.\nDet giver mulighed for en anderledes og aktiv oplevelse, hvor alle kan være med.",
        },
        {
          image: "/photos/hof-van-eckberge-lasergame-reserveren.jpg",
          alt: "Arrangement 4",
          title: "Mobil lasergame – vi kommer til jer",
          text: "Med den mobile løsning fra OX-Consult kan lasergame rykkes ud til jeres egen lokation.\nPerfekt til skoler, events eller firmaer, der ønsker en fleksibel løsning.",
        },
        {
          image: "/photos/skærmbillede 2026-04-20 kl. 11.12.45.png",
          alt: "Arrangement 5",
          title: "Fleksible faciliteter",
          text: "Har I en hal eller et større lokale, kan det nemt omdannes til en lasergame-bane ved brug af borde, kasser eller gymnastikredskaber.\nDet giver en unik og tilpasset oplevelse i jeres egne rammer.",
        },      ],
    };
  },
  methods: {
    slideRight() {
      this.direction = 'right';
      this.current = (this.current + 1) % this.slides.length;
    },
    slideLeft() {
      this.direction = 'left';
      this.current = (this.current - 1 + this.slides.length) % this.slides.length;
    },
  },
};
</script>

