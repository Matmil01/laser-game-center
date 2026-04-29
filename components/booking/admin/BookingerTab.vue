<template>
  <div class="bg-black border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] p-6 mb-8">
    <h2 class="font-black text-lg tracking-wide mb-4 text-white">Sæt tilgængelighed</h2>
    <form @submit.prevent="setWindow">
      <div class="flex flex-col sm:flex-row gap-5">
        <DatePicker
          :min-date="today"
          :attributes="addSlotCalAttrs"
          @dayclick="onAddSlotDayClick"
        />
        <div class="flex flex-col gap-4 justify-between py-1 flex-1">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 mb-1">Valgt dato</p>
            <p class="text-sm font-semibold text-zinc-200">{{ newDate ? formatDate(newDate) : 'Ingen dato valgt' }}</p>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium mb-1 text-white">Fra</label>
              <select
                v-model="windowFrom"
                required
                class="border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white cursor-pointer w-full text-sm"
              >
                <option v-for="t in timeSlotOptions" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1 text-white">Til</label>
              <select
                v-model="windowTo"
                required
                class="border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white cursor-pointer w-full text-sm"
              >
                <option v-for="t in windowToOptions" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
          </div>
          <p class="text-xs text-zinc-500">{{ windowPreviewText }}</p>
          <button
            type="submit"
            :disabled="addLoading || !newDate"
            class="bg-black text-white border-2 border-neonred shadow-[0_0_8px_1px_var(--color-neonred)] px-5 py-2 font-bold tracking-wide hover:border-neongreen hover:shadow-[0_0_8px_1px_var(--color-neongreen)] disabled:opacity-50 cursor-pointer transition"
          >
            {{ addLoading ? 'Gemmer…' : 'Gem tilgængelighed' }}
          </button>
        </div>
      </div>
    </form>
    <p v-if="addInfo" class="mt-2 text-sm text-neongreen">{{ addInfo }}</p>
    <p v-if="addError" class="mt-2 text-sm text-neonred">{{ addError }}</p>
  </div>

  <AdminCalendar
    ref="calendarRef"
    :slots="events"
    :today="today"
    :slots-loading="slotsLoading"
    :load-error="loadError"
    @refresh="loadData"
    @delete-slot="deleteWindow"
    @cancel-booking="cancelBooking"
    @select-date="selectDate"
    @open-slot="openEvent"
  />

  <SlotModal
    v-if="editingSlot"
    :slot="editingSlot"
    :deleting-id="deletingId"
    :cancelling-id="cancellingId"
    :saving="modalSaving"
    :save-error="modalSaveError"
    @close="editingSlot = null; modalSaveError = ''"
    @delete-slot="deleteWindow"
    @cancel-booking="cancelBooking"
    @update-slot="updateWindow"
  />
</template>

<script setup>
import AdminCalendar from '~/components/booking/admin/AdminCalendar.vue'
import SlotModal from '~/components/booking/admin/SlotModal.vue'
import DatePicker from '~/components/booking/DatePicker.vue'

const props = defineProps({
  password: String,
  authed:   Boolean
})

const emit = defineEmits(['unauthorized'])

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl

const windows      = ref([])
const bookings     = ref([])
const slotsLoading = ref(false)
const newDate      = ref('')
const windowFrom   = ref('10:00')
const windowTo     = ref('20:00')
const addLoading   = ref(false)
const addError     = ref('')
const addInfo      = ref('')
const deletingId     = ref(null)
const cancellingId   = ref(null)
const editingSlot    = ref(null)
const loadError      = ref('')
const calendarRef    = ref(null)
const modalSaving    = ref(false)
const modalSaveError = ref('')

const windowToOptions = computed(() => {
  const [h, m] = windowFrom.value.split(':').map(Number)
  const fromMin = h * 60 + m
  return timeSlotOptions.filter(t => {
    const [th, tm] = t.split(':').map(Number)
    return th * 60 + tm > fromMin
  })
})

const windowPreviewText = computed(() => {
  if (!windowFrom.value || !windowTo.value) return ''
  const [fh, fm] = windowFrom.value.split(':').map(Number)
  const [th, tm] = windowTo.value.split(':').map(Number)
  const mins = (th * 60 + tm) - (fh * 60 + fm)
  if (mins <= 0) return ''
  const h = Math.floor(mins / 60)
  const m = mins % 60
  const label = h > 0 ? `${h} time${h !== 1 ? 'r' : ''}${m ? ` ${m} min` : ''}` : `${m} min`
  return `${label} tilgængelighed – op til ${Math.floor(mins / 30)} spilstarttider`
})

const events = computed(() => {
  const result = []
  for (const w of windows.value) {
    const [sh, sm] = w.start_time.slice(0, 5).split(':').map(Number)
    const [eh, em] = w.end_time.slice(0, 5).split(':').map(Number)
    result.push({
      id:          `w-${w.id}`,
      _type:       'window',
      _window_id:  w.id,
      slot_date:   w.window_date,
      slot_time:   w.start_time,
      duration_min: (eh * 60 + em) - (sh * 60 + sm),
      is_booked:   false,
      name: null, email: null, phone: null, note: null, participants: null, num_games: null,
    })
  }
  for (const b of bookings.value) {
    result.push({
      id:          `b-${b.id}`,
      _type:       'booking',
      _booking_id: b.id,
      _window_id:  b.window_id,
      slot_date:   b.window_date,
      slot_time:   b.start_time,
      duration_min: b.num_games * 30,
      is_booked:   true,
      name: b.name, email: b.email, phone: b.phone, note: b.note,
      participants: b.participants, num_games: b.num_games,
    })
  }
  return result
})

const addSlotCalAttrs = computed(() => {
  const attrs = []
  const winDates = windows.value.map(w => new Date(w.window_date + 'T00:00:00'))
  if (winDates.length) attrs.push({ highlight: { color: 'green', fillMode: 'light' }, dates: winDates })
  if (newDate.value) attrs.push({ highlight: { color: 'gray', fillMode: 'solid' }, dates: [new Date(newDate.value + 'T00:00:00')] })
  return attrs
})

function onAddSlotDayClick(day) {
  if (!day.isDisabled) {
    newDate.value = calToKey(day.date)
    const existing = windows.value.find(w => w.window_date === newDate.value)
    if (existing) {
      windowFrom.value = existing.start_time.slice(0, 5)
      windowTo.value   = existing.end_time.slice(0, 5)
    }
  }
}

const today = calToKey(new Date())

async function loadData(navigate = false) {
  slotsLoading.value = true
  loadError.value    = ''
  try {
    const res = await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'list', pw: props.password },
    })
    windows.value  = res.windows  ?? []
    bookings.value = res.bookings ?? []
    if (res.db_error) loadError.value = res.db_error
    if (navigate && newDate.value) calendarRef.value?.navigateTo(newDate.value, true)
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    loadError.value = e.data?.error ?? 'Kunne ikke hente data.'
  } finally {
    slotsLoading.value = false
  }
}

async function setWindow() {
  addLoading.value = true
  addError.value   = ''
  addInfo.value    = ''
  try {
    const res = await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'set_window', pw: props.password, date: newDate.value, from: windowFrom.value, to: windowTo.value },
    })
    await loadData(true)
    addInfo.value = res.created
      ? `Tilgængelighed oprettet: kl. ${windowFrom.value}–${windowTo.value}`
      : `Tilgængelighed opdateret: kl. ${windowFrom.value}–${windowTo.value}`
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    addError.value = e.data?.error ?? 'Kunne ikke gemme tilgængelighed.'
  } finally {
    addLoading.value = false
  }
}

async function deleteWindow(compositeId) {
  const windowId = parseInt(String(compositeId).replace('w-', ''))
  deletingId.value = compositeId
  try {
    await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'delete_window', pw: props.password, id: windowId },
    })
    await loadData()
    if (editingSlot.value?.id === compositeId) editingSlot.value = null
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    alert(e.data?.error ?? 'Kunne ikke slette tidsramme.')
  } finally {
    deletingId.value = null
  }
}

async function cancelBooking(compositeId) {
  const bookingId = parseInt(String(compositeId).replace('b-', ''))
  cancellingId.value = compositeId
  try {
    await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'cancel', pw: props.password, id: bookingId },
    })
    await loadData()
    editingSlot.value = null
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    alert(e.data?.error ?? 'Kunne ikke annullere booking.')
  } finally {
    cancellingId.value = null
  }
}

async function updateWindow({ id: compositeId, from, to }) {
  const windowId = parseInt(String(compositeId).replace('w-', ''))
  modalSaving.value    = true
  modalSaveError.value = ''
  try {
    await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'update_window', pw: props.password, id: windowId, from, to },
    })
    await loadData()
    editingSlot.value = null
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    modalSaveError.value = e.data?.error ?? 'Kunne ikke opdatere tidsramme.'
  } finally {
    modalSaving.value = false
  }
}

function selectDate(dateKey) {
  newDate.value = dateKey
  const existing = windows.value.find(w => w.window_date === dateKey)
  if (existing) {
    windowFrom.value = existing.start_time.slice(0, 5)
    windowTo.value   = existing.end_time.slice(0, 5)
  }
}

function openEvent(event) {
  editingSlot.value = event
}

onMounted(() => {
  if (props.authed) loadData()
})
</script>
