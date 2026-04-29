
<template>
  <div class="w-300 mx-auto px-6 py-10">
    <div class="max-w-lg">
      <h1 class="text-3xl text-white mb-8 tracking-wide">Book tid</h1>

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
</template>

<script setup>
import BookingForm from '~/components/booking/public/BookingForm.vue'
import DatePicker from '~/components/booking/DatePicker.vue'

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