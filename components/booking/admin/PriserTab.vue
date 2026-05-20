<template>
  <div class="bg-black border-neon-subtle-neonred p-6 mb-2">
    <h2 class="font-black text-lg mb-6 tracking-wide text-white">Priser</h2>
    <form @submit.prevent="saveSettings" class="flex flex-col gap-3 max-w-sm">
      <div class="grid grid-cols-[auto_1fr_1fr] gap-x-3 items-center mb-1">
        <span class="text-xs text-zinc-500"></span>
        <span class="text-xs text-zinc-500 text-center">DKK</span>
        <span class="text-xs text-zinc-500 text-center">EUR</span>
      </div>
      <div v-for="n in 4" :key="n" class="grid grid-cols-[auto_1fr_1fr] items-center gap-x-3">
        <label class="text-sm text-white pr-2 whitespace-nowrap">{{ n }} spil</label>
        <div class="flex items-center gap-1 min-w-0">
          <input
            v-model="prices[n]"
            type="number"
            min="1"
            max="9999"
            step="1"
            required
            class="border-neon-subtle-neonred px-3 py-2 focus:outline-none text-sm bg-black text-white w-full min-w-0"
          />
          <span class="text-sm text-zinc-500">kr</span>
        </div>
        <div class="flex items-center gap-1 min-w-0">
          <input
            v-model="eurPrices[n]"
            type="number"
            min="1"
            max="9999"
            step="1"
            required
            class="border-neon-subtle-neonred px-3 py-2 focus:outline-none text-sm bg-black text-white w-full min-w-0"
          />
          <span class="text-sm text-zinc-500">€</span>
        </div>
      </div>
      <button
        type="submit"
        :disabled="saving"
        class="mt-2 bg-black text-white border-neon-subtle-neonred px-5 py-2 font-bold tracking-wide hover:border-neon-subtle-neongreen disabled:opacity-50 cursor-pointer self-start transition"
      >
        {{ saving ? 'Gemmer…' : 'Gem' }}
      </button>
      <p v-if="saveInfo" class="text-sm text-neongreen">{{ saveInfo }}</p>
      <p v-if="saveError" class="text-sm text-neonred">{{ saveError }}</p>
    </form>
  </div>
</template>

<script setup>
// Admin-fane til at redigere priser per antal spil i DKK og EUR.

import { FALLBACK_DKK, FALLBACK_EUR } from '~/composables/usePrices.js'

const props = defineProps({
  password: String,
  authed:   Boolean,
})
const emit = defineEmits(['unauthorized', 'dirty-change'])

const config = useRuntimeConfig()
const apiUrl = config.public.apiUrl

// Priser for 1–4 spil – forudfyldt med fallback-værdier indtil API svarer
const prices    = reactive({ 1: String(FALLBACK_DKK[0]), 2: String(FALLBACK_DKK[1]), 3: String(FALLBACK_DKK[2]), 4: String(FALLBACK_DKK[3]) })
const eurPrices = reactive({ 1: String(FALLBACK_EUR[0]), 2: String(FALLBACK_EUR[1]), 3: String(FALLBACK_EUR[2]), 4: String(FALLBACK_EUR[3]) })
const saving    = ref(false)
const saveError = ref('')
const saveInfo  = ref('')
const loaded    = ref(false)

// Markér formularen som ændret når priser opdateres
watch(
  () => ({ ...prices, ...eurPrices }),
  () => { if (loaded.value) emit('dirty-change', true) }
)

// Hent priser fra API og overskriv fallback
async function loadSettings() {
  try {
    const data = await $fetch(`${apiUrl}/settings.php`)
    for (const n of [1, 2, 3, 4]) {
      if (data[`price_${n}`])     prices[n]    = data[`price_${n}`]
      if (data[`price_eur_${n}`]) eurPrices[n] = data[`price_eur_${n}`]
    }
    // Vent til næste tick så watchers ikke trigger dirty-flag ved indlæsning
    await nextTick()
    loaded.value = true
  } catch {}
}

// Gem priser via API og opdater det globale settings-objekt så resten af appen ser de nye priser
async function saveSettings() {
  saving.value    = true
  saveError.value = ''
  saveInfo.value  = ''
  try {
    await $fetch(`${apiUrl}/settings.php`, {
      method: 'POST',
      body: {
        pw:          props.password,
        price_1:     String(prices[1]),
        price_2:     String(prices[2]),
        price_3:     String(prices[3]),
        price_4:     String(prices[4]),
        price_eur_1: String(eurPrices[1]),
        price_eur_2: String(eurPrices[2]),
        price_eur_3: String(eurPrices[3]),
        price_eur_4: String(eurPrices[4]),
      },
    })
    saveInfo.value = 'Gemt!'
    emit('dirty-change', false)
    const { settings } = useSettings()
    const fresh = await $fetch(`${apiUrl}/settings.php`)
    settings.value = { ...(settings.value ?? {}), ...fresh }
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    saveError.value = e.data?.error ?? 'Kunne ikke gemme.'
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  if (props.authed) loadSettings()
})
</script>
