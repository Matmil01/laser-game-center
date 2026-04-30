<template>
  <div class="bg-black border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] p-6 mb-2">
    <h2 class="font-black text-lg mb-4 tracking-wide text-white">Aktuelt</h2>
    <form @submit.prevent="saveAktuelt" class="flex flex-col gap-4 max-w-sm">
      <div>
        <button
          type="button"
          @click="settingsAktueltVisible = !settingsAktueltVisible"
          class="flex items-center gap-3 cursor-pointer group"
        >
          <span
            class="w-5 h-5 border-2 flex items-center justify-center transition"
            :class="settingsAktueltVisible ? 'border-neongreen' : 'border-zinc-600'"
          >
            <span v-if="settingsAktueltVisible" class="w-2.5 h-2.5 bg-neongreen block" />
          </span>
          <span class="text-sm font-medium text-white">Vis på hjemmeside</span>
        </button>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Titel</label>
        <input
          v-model="settingsAktueltTitle"
          type="text"
          maxlength="100"
          placeholder="AKTUELT"
          class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white"
        />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Tekst</label>
        <textarea
          v-model="settingsAktueltText"
          maxlength="500"
          rows="4"
          placeholder="Skriv tekst"
          class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white resize-y"
        />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Ikon</label>
        <div class="flex flex-wrap gap-3">
          <button
            v-for="icon in ['icon1', 'icon2', 'icon3', 'icon4', 'icon5', 'icon6', 'icon7', 'icon8', 'icon9']"
            :key="icon"
            type="button"
            @click="settingsAktueltIcon = icon"
            :class="['p-2 border-2 transition cursor-pointer', settingsAktueltIcon === icon ? 'border-white shadow-[0_0_6px_1px_white]' : 'border-zinc-600 hover:border-zinc-400']"
          >
              <!-- mask-image trick. No need to inline the SVG markup. -->
            <div
              class="w-12 h-12 shrink-0"
              :style="{
                backgroundColor: settingsAktueltIcon === icon ? settingsAktueltColor : '#ffffff',
                maskImage: `url('/icons/${icon}.svg')`,
                WebkitMaskImage: `url('/icons/${icon}.svg')`,
                maskSize: 'contain',
                WebkitMaskSize: 'contain',
                maskRepeat: 'no-repeat',
                WebkitMaskRepeat: 'no-repeat',
                maskPosition: 'center',
                WebkitMaskPosition: 'center',
              }"
            />
          </button>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Kantfarve</label>
        <div class="flex gap-3 flex-wrap">
          <button
            v-for="c in themeColors"
            :key="c.hex"
            type="button"
            @click="settingsAktueltColor = c.hex"
            :title="c.name"
            class="w-9 h-9 border-2 transition cursor-pointer"
            :style="{
              backgroundColor: c.hex,
              borderColor: settingsAktueltColor === c.hex ? 'white' : 'transparent',
              boxShadow: settingsAktueltColor === c.hex ? `0 0 8px 2px ${c.hex}` : 'none'
            }"
          />
        </div>
      </div>
      <button
        type="submit"
        :disabled="aktueltSaving"
        class="bg-black text-white border-2 border-neonred shadow-[0_0_8px_1px_var(--color-neonred)] px-5 py-2 font-bold tracking-wide hover:border-neongreen hover:shadow-[0_0_8px_1px_var(--color-neongreen)] disabled:opacity-50 cursor-pointer transition w-full"
      >
        {{ aktueltSaving ? 'Gemmer…' : 'Gem' }}
      </button>
      <p v-if="aktueltSaveInfo" class="text-sm text-neongreen">{{ aktueltSaveInfo }}</p>
      <p v-if="aktueltSaveError" class="text-sm text-neonred">{{ aktueltSaveError }}</p>
    </form>
    <!-- Live preview -->
    <div class="mt-8">
      <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-3">Forhåndsvisning</p>
      <div
        v-if="settingsAktueltVisible && settingsAktueltText"
        class="max-w-md w-full mx-auto px-6 py-5 bg-black text-white border-2 transition-all"
        :style="{
          borderColor: settingsAktueltColor,
          boxShadow: `0 0 18px 2px ${settingsAktueltColor}`
        }"
      >
        <div class="flex items-center gap-4">
          <div
            class="w-16 h-16 shrink-0"
            :style="{
              backgroundColor: settingsAktueltColor,
              maskImage: `url('/icons/${settingsAktueltIcon}.svg')`,
              WebkitMaskImage: `url('/icons/${settingsAktueltIcon}.svg')`,
              maskSize: 'contain',
              WebkitMaskSize: 'contain',
              maskRepeat: 'no-repeat',
              WebkitMaskRepeat: 'no-repeat',
              maskPosition: 'center',
              WebkitMaskPosition: 'center',
            }"
          />
          <div>
            <h2 class="text-lg font-black tracking-wide mb-1">{{ settingsAktueltTitle || 'AKTUELT' }}</h2>
            <p class="text-sm leading-relaxed">{{ settingsAktueltText }}</p>
          </div>
        </div>
      </div>
      <p v-else class="text-sm text-zinc-500 italic">Boksen er skjult eller har ingen tekst.</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  password: String,
  authed: Boolean
})
const emit = defineEmits(['update-aktuelt-info', 'unauthorized', 'dirty-change'])

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl

const settingsAktueltTitle   = ref('AKTUELT')
const settingsAktueltText    = ref('')
const settingsAktueltIcon    = ref('icon1')
const settingsAktueltColor   = ref('#FF9D00')
const settingsAktueltVisible = ref(true)
const aktueltSaving          = ref(false)
const aktueltSaveError       = ref('')
const aktueltSaveInfo        = ref('')
const aktueltLoaded          = ref(false)

watch(
  [settingsAktueltTitle, settingsAktueltText, settingsAktueltIcon, settingsAktueltColor, settingsAktueltVisible],
  () => { if (aktueltLoaded.value) emit('dirty-change', true) }
)

const themeColors = [
  { name: 'NeonRed',    hex: '#FF0000' },
  { name: 'NeonPink',   hex: '#FF009D' },
  { name: 'NeonOrange', hex: '#FF9D00' },
  { name: 'NeonBlue',    hex: '#00C3FF' },
  { name: 'NeonGreen',   hex: '#00FF00' },
]

async function loadSettings() {
  try {
    const data = await $fetch(`${apiUrl}/settings.php`)
    settingsAktueltTitle.value   = data.aktuelt_title   ?? 'AKTUELT'
    settingsAktueltText.value    = data.aktuelt_text    ?? ''
    settingsAktueltIcon.value    = data.aktuelt_icon    ?? 'icon1'
    settingsAktueltColor.value   = data.aktuelt_color   ?? '#FF9D00'
    settingsAktueltVisible.value = data.aktuelt_visible !== '0'
    emit('update-aktuelt-info', { ...data })
    await nextTick()
    aktueltLoaded.value = true
  } catch {}
}

async function saveAktuelt() {
  aktueltSaving.value    = true
  aktueltSaveError.value = ''
  aktueltSaveInfo.value  = ''
  try {
    await $fetch(`${apiUrl}/settings.php`, {
      method: 'POST',
      body: {
        pw: props.password,
        aktuelt_title: settingsAktueltTitle.value,
        aktuelt_text: settingsAktueltText.value,
        aktuelt_icon: settingsAktueltIcon.value,
        aktuelt_color: settingsAktueltColor.value,
        aktuelt_visible: settingsAktueltVisible.value ? '1' : '0',
      },
    })
    aktueltSaveInfo.value = 'Gemt!'
    emit('dirty-change', false)
    emit('update-aktuelt-info', {
      aktuelt_title: settingsAktueltTitle.value,
      aktuelt_text: settingsAktueltText.value,
      aktuelt_icon: settingsAktueltIcon.value,
      aktuelt_color: settingsAktueltColor.value,
      aktuelt_visible: settingsAktueltVisible.value ? '1' : '0',
    })
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    aktueltSaveError.value = e.data?.error ?? 'Kunne ikke gemme.'
  } finally {
    aktueltSaving.value = false
  }
}

onMounted(() => {
  if (props.authed) loadSettings()
})
</script>