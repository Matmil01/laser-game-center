<template>
  <form @submit.prevent="submitBooking" class="space-y-6">

    <!-- Starttidspunkter -->
    <div v-if="date">
      <label class="block text-sm font-medium mb-2 text-white">Vælg starttidspunkt</label>
      <p v-if="slotsLoading" class="text-sm text-zinc-400">Henter ledige tider…</p>
      <p v-else-if="slots.length === 0" class="text-sm text-zinc-500">Ingen ledige tider på denne dag.</p>
      <div v-else class="grid grid-cols-3 gap-2">
        <button
          v-for="slot in slots"
          :key="slot.start_time"
          type="button"
          :class="[
            'border px-3 py-2 text-sm font-medium transition-colors cursor-pointer',
            selectedTime === slot.start_time
              ? 'bg-neonred text-white border-neonred'
              : 'border-zinc-600 text-zinc-300 hover:border-neonred hover:text-white'
          ]"
          @click="selectTime(slot)"
        >
          {{ slot.start_time }}
        </button>
      </div>
    </div>

    <!-- Antal spil -->
    <div v-if="selectedTime">
      <label class="block text-sm font-medium mb-2 text-white">
        Antal spil: <span class="font-bold">{{ numGames }}</span>
        <span class="block text-zinc-400 font-normal text-xs mt-0.5">{{ numGames * 30 }} min · kl. {{ selectedTime }}–{{ endTimePreview }}</span>
      </label>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="n in selectedMaxGames"
          :key="n"
          type="button"
          :class="[
            'px-4 py-2 border text-sm font-semibold transition-colors cursor-pointer',
            numGames === n
              ? 'bg-neonred border-neonred text-white'
              : 'border-zinc-600 text-zinc-300 hover:border-neonred hover:text-white'
          ]"
          @click="numGames = n"
        >
          {{ n }} spil
        </button>
      </div>
    </div>

    <template v-if="selectedTime && numGames">

      <!-- Antal deltagere -->
      <div>
        <label class="block text-sm font-medium mb-2 text-white">Antal deltagere: <span class="font-bold">{{ participants }}</span></label>
        <input
          v-model.number="participants"
          type="range"
          min="4"
          max="99"
          class="w-full accent-neonred cursor-pointer"
        />
        <div class="flex justify-center items-center gap-3 mt-2">
          <button
            type="button"
            :disabled="participants <= 4"
            class="w-9 h-9 border border-zinc-600 text-lg font-bold hover:border-neonred text-zinc-300 hover:text-white disabled:opacity-30 transition-colors cursor-pointer"
            @click="participants = Math.max(4, participants - 1)"
          >−</button>
          <button
            type="button"
            :disabled="participants >= 99"
            class="w-9 h-9 border border-zinc-600 text-lg font-bold hover:border-neonred text-zinc-300 hover:text-white disabled:opacity-30 transition-colors cursor-pointer"
            @click="participants = Math.min(99, participants + 1)"
          >+</button>
        </div>
      </div>

      <!-- Kontaktoplysninger -->
      <div class="space-y-4 pt-2 border-t border-zinc-700">
        <!-- Honeypot -->
        <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
          <label for="website">Website</label>
          <input id="website" v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 cursor-pointer text-white">Navn</label>
          <input v-model="form.name" type="text" required placeholder="Dit fulde navn" class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 cursor-pointer text-white">Email</label>
          <input v-model="form.email" type="email" required placeholder="din@mail.dk" class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 cursor-pointer text-white">Telefon</label>
          <input v-model="form.phone" type="tel" required placeholder="Dit telefonnummer" class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 cursor-pointer text-white">Besked <span class="text-zinc-500 font-normal">(valgfri)</span></label>
          <textarea v-model="form.note" rows="3" placeholder="Skriv gerne hvad du ønsker hjælp til…" class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 resize-none" />
        </div>
      </div>

      <p v-if="error" class="text-sm text-neonred">{{ error }}</p>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-black text-white border-2 border-neonred shadow-[0_0_8px_1px_var(--color-neonred)] py-3 font-bold tracking-wide hover:border-neongreen hover:shadow-[0_0_8px_1px_var(--color-neongreen)] disabled:opacity-50 transition-colors"
      >
        {{ loading ? 'Sender…' : 'Bekræft booking' }}
      </button>
    </template>

  </form>
</template>

<script setup>
const props = defineProps({
  date: {
    type: [Date, String],
    default: null
  }
})

const emit = defineEmits(['success', 'refresh-dates'])

const config = useRuntimeConfig()
const apiUrl = config.public.apiUrl

const slots            = ref([])
const selectedTime     = ref(null)
const selectedMaxGames = ref(4)
const numGames         = ref(1)
const participants     = ref(4)
const slotsLoading     = ref(false)
const loading          = ref(false)
const error            = ref('')
const form             = reactive({ name: '', email: '', phone: '', note: '', website: '' })

const endTimePreview = computed(() => {
  if (!selectedTime.value) return ''
  const [h, m] = selectedTime.value.split(':').map(Number)
  const end    = h * 60 + m + numGames.value * 30
  return `${String(Math.floor(end / 60)).padStart(2, '0')}:${String(end % 60).padStart(2, '0')}`
})

watch(() => props.date, async (newDate) => {
  selectedTime.value     = null
  selectedMaxGames.value = 4
  numGames.value         = 1
  slots.value            = []
  error.value            = ''
  if (!newDate) {
    participants.value = 4
    Object.assign(form, { name: '', email: '', phone: '', note: '', website: '' })
    return
  }
  slotsLoading.value = true
  try {
    const dateStr = newDate instanceof Date ? calToKey(newDate) : newDate
    slots.value = await $fetch(`${apiUrl}/slots.php?date=${dateStr}`)
  } catch {
    slots.value = []
  } finally {
    slotsLoading.value = false
  }
})

function selectTime(slot) {
  selectedTime.value     = slot.start_time
  selectedMaxGames.value = slot.max_games
  numGames.value         = 1
}

async function submitBooking() {
  loading.value = true
  error.value   = ''
  try {
    const dateStr = props.date instanceof Date ? calToKey(props.date) : props.date
    const res = await $fetch(`${apiUrl}/book.php`, {
      method: 'POST',
      body: {
        ...form,
        date:         dateStr,
        start_time:   selectedTime.value,
        num_games:    numGames.value,
        participants: participants.value,
      },
    })
    emit('success', res)
    emit('refresh-dates')
  } catch (e) {
    error.value = e.data?.error ?? 'Noget gik galt. Prøv igen.'
  } finally {
    loading.value = false
  }
}
</script>
