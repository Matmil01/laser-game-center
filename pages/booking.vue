
<template>
  <div class="max-w-lg mx-auto px-6 py-10">
    <h1 class="text-3xl font-black mb-8 tracking-wide">Book tid</h1>

    <p class="mb-8 text-white font-semibold">OBS! Minimum 4 personer til en booking</p>

    <div v-if="success" class="bg-black border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] p-6 text-center">
      <p class="text-xl font-semibold text-neongreen mb-1">Booking bekræftet!</p>
      <p class="text-neongreen">{{ success.date }} kl. {{ success.time }}–{{ success.end_time }}</p>
      <p class="text-neongreen text-sm mt-1">{{ success.num_games }} spil · {{ success.num_games * 30 }} minutter</p>
      <p class="text-sm text-zinc-400 mt-2">En bekræftelse er sendt til din email.</p>
      <button
        class="mt-6 text-sm text-zinc-400 underline cursor-pointer hover:text-white"
        @click="reset"
      >Book en ny tid</button>
    </div>

    <!-- Booking form -->
    <form v-else @submit.prevent="submitBooking" class="space-y-6">

      <!-- Date picker -->
      <div>
        <label class="block text-sm font-medium mb-1 cursor-pointer">Vælg dato</label>
        <DatePicker
          is-expanded
          :min-date="today"
          :attributes="calendarAttributes"
          @dayclick="onDayClick"
        />
      </div>

      <!-- starttidspunkter -->
      <div v-if="date">
        <label class="block text-sm font-medium mb-2">Vælg starttidspunkt</label>
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

      <!-- Antal spil selector -->
      <div v-if="selectedTime">
        <label class="block text-sm font-medium mb-2">
          Antal spil: <span class="font-bold">{{ numGames }}</span>
          <span class="text-zinc-400 font-normal ml-2">({{ numGames * 30 }} min - kl. {{ selectedTime }}–{{ endTimePreview }})</span>
        </label>
        <div class="flex gap-2">
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

      <!-- Kontakt og deltagere -->
      <template v-if="selectedTime && numGames">
        <!-- Deltager count -->
        <div class="mt-4">
          <label class="block text-sm font-medium mb-2">Antal deltagere: <span class="font-bold">{{ participants }}</span></label>
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

        <!-- Contact form -->
        <BookingForm :form="form" />

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
  </div>
</template>

<script setup>
import BookingForm from '~/components/booking/public/BookingForm.vue'
import DatePicker from '~/components/booking/DatePicker.vue'

const config = useRuntimeConfig()
const apiUrl = config.public.apiUrl
const today  = new Date()

const date             = ref(null)
const slots            = ref([])
const selectedTime     = ref(null)
const selectedMaxGames = ref(4)
const numGames         = ref(1)
const participants     = ref(4)
const slotsLoading     = ref(false)
const loading          = ref(false)
const success          = ref(null)
const error            = ref('')
const form             = reactive({ name: '', email: '', phone: '', note: '', website: '' })
const availableDates   = ref([])

// Beregner sluttidspunktet til visning ud fra valgt starttid og antal spil
const endTimePreview = computed(() => {
  if (!selectedTime.value) return ''
  const [h, m] = selectedTime.value.split(':').map(Number)
  const end    = h * 60 + m + numGames.value * 30
  return `${String(Math.floor(end / 60)).padStart(2, '0')}:${String(end % 60).padStart(2, '0')}`
})

const route = useRoute()
// Nulstiller bookingflow når bruger navigerer væk
watch(
  () => route.fullPath,
  (newPath, oldPath) => {
    if (newPath === '/' && oldPath !== '/') reset()
  }
)

async function loadAvailableDates() {
  try {
    const dates = await $fetch(`${apiUrl}/available-dates.php`)
    availableDates.value = dates.map(d => {
      const [y, m, day] = d.split('-').map(Number)
      return new Date(y, m - 1, day)
    })
  } catch {
    availableDates.value = []
  }
}
onMounted(loadAvailableDates)

const calendarAttributes = computed(() => [
  ...(availableDates.value.length ? [{
    highlight: { color: 'green', fillMode: 'light' },
    dates: availableDates.value,
  }] : []),
  ...(date.value ? [{
    highlight: { color: 'blue', fillMode: 'solid' },
    dates: [date.value],
  }] : []),
])

async function loadSlotsForDate(selected) {
  selectedTime.value     = null
  selectedMaxGames.value = 4
  numGames.value         = 1
  slots.value            = []
  if (!selected) return
  slotsLoading.value = true
  try {
    const dateStr = selected instanceof Date ? calToKey(selected) : selected
    slots.value = await $fetch(`${apiUrl}/slots.php?date=${dateStr}`)
  } catch {
    slots.value = []
  } finally {
    slotsLoading.value = false
  }
}

function onDayClick(day) {
  if (!day.isDisabled) {
    date.value = day.date
    loadSlotsForDate(day.date)
  }
}

function selectTime(slot) {
  selectedTime.value     = slot.start_time
  selectedMaxGames.value = slot.max_games
  numGames.value         = 1
}

async function submitBooking() {
  loading.value = true
  error.value   = ''
  try {
    const dateStr = date.value instanceof Date ? calToKey(date.value) : date.value
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
    success.value = res
    loadAvailableDates()
  } catch (e) {
    error.value = e.data?.error ?? 'Noget gik galt. Prøv igen.'
  } finally {
    loading.value = false
  }
}

function reset() {
  success.value          = null
  error.value            = ''
  date.value             = null
  slots.value            = []
  selectedTime.value     = null
  selectedMaxGames.value = 4
  numGames.value         = 1
  participants.value     = 4
  Object.assign(form, { name: '', email: '', phone: '', note: '', website: '' })
}
</script>