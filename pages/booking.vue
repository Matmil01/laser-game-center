<template>
  <div>
      <div class="sm:h-[570px] h-90">
          <div
              class="absolute mt-15 top-0 left-0 w-full sm:h-[570px] h-90 overflow-hidden z-0"
          >
              <img
                  src="/photos/hero.webp"
                  aria-hidden="true"
                  class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none blur-sm scale-105"
              />
              <video
                  src="/videos/hero.mp4"
                  autoplay
                  loop
                  muted
                  playsinline
                  preload="none"
                  class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none"
                  style="min-width: 100%; min-height: 320px"
              ></video>
          </div>
          <div class="relative z-10 flex items-center justify-center h-full">
              <h1
                  class="mt-64 text-white text-center text-3xl sm:text-5xl z-10 bg-black/30 px-2 py-2 backdrop-blur-md rounded-xl"
              >BOOK TID
              </h1>
          </div>
      </div>
  <div class="flex items-center justify-center">
  <div class="w-full max-w-2xl mx-auto px-4 py-10">
    <div>

      <p class="mb-8 text-white font-semibold">OBS! Minimum 4 personer til en booking</p>
      <p v-if="!selectedDateHasSlots" class="mb-8 text-white font-semibold">
        Ingen ledige tider? Ring til os på {{ contact.phone }} — så finder vi en løsning.
      </p>

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

      <div v-else class="space-y-6">
        <div>
          <label class="block text-sm font-medium mb-1 text-white cursor-pointer">Vælg dato</label>
          <DatePicker
            is-expanded
            :min-date="today"
            :attributes="calendarAttributes"
            @dayclick="onDayClick"
          />

        </div>
        <BookingForm
          :date="date"
          @success="onSuccess"
          @refresh-dates="loadAvailableDates"
        />
      </div>
    </div>
  </div>
  </div>
  </div>
</template>

<script setup>
import BookingForm from '~/components/booking/public/BookingForm.vue'
import DatePicker from '~/components/booking/DatePicker.vue'

const { contact, fetchContactInfo } = useContactInfo()

const config = useRuntimeConfig()
const apiUrl = config.public.apiUrl
const today  = new Date()

const date           = ref(null)
const availableDates = ref([])
const success        = ref(null)

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
onMounted(() => { fetchContactInfo(); loadAvailableDates() })

const selectedDateHasSlots = computed(() =>
  date.value && availableDates.value.some(d => d.toDateString() === date.value.toDateString())
)

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

function onDayClick(day) {
  if (!day.isDisabled) date.value = day.date
}

function onSuccess(res) {
  success.value = res
}

function reset() {
  success.value = null
  date.value    = null
}
</script>
