<template>
  <div class="bg-black text-white border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-black text-lg tracking-wide text-white">Kalender</h2>
      <button
        class="text-sm text-neonred hover:text-neonred/70 disabled:opacity-40 cursor-pointer"
        :disabled="slotsLoading"
        @click="$emit('refresh')"
      >
        {{ slotsLoading ? 'Henter…' : 'Opdater' }}
      </button>
    </div>
    <p v-if="slotsLoading && slots.length === 0" class="text-sm text-zinc-400">Henter tider…</p>
    <p v-else-if="loadError" class="text-sm text-neonred">{{ loadError }}</p>
    <div v-else>

      <!-- Toolbar -->
      <div class="flex items-center gap-3 mb-3 flex-wrap">
        <div class="flex gap-0.75">
          <button
            class="px-2.75 py-1 border border-neonred text-white cursor-pointer text-[0.82rem] font-medium hover:border-neongreen transition"
            @click="calNavigate(-1)"
          >&lt;&lt;</button>
          <button
            class="px-2.75 py-1 border border-neonred text-white cursor-pointer text-[0.82rem] font-bold tracking-[0.04em] hover:border-neongreen transition"
            @click="calGoToToday"
          >I DAG</button>
          <button
            class="px-2.75 py-1 border border-neonred text-white cursor-pointer text-[0.82rem] font-medium hover:border-neongreen transition"
            @click="calNavigate(1)"
          >&gt;&gt;</button>
        </div>
        <span class="flex-1 text-[0.9rem] font-semibold text-zinc-300 text-center truncate min-w-0">{{ calRangeLabel }}</span>
        <div class="flex overflow-hidden">
          <button
            type="button"
            :class="[
              'py-1 px-3.5 border-2 cursor-pointer text-[0.82rem] transition font-semibold',
              calViewMode === 'day'
                ? 'border-neongreen text-white'
                : 'border-neonred text-zinc-300 hover:text-white'
            ]"
            @click="calViewMode = 'day'"
          >Dag</button>
          <!-- Mobile: week grid -->
          <button
            type="button"
            :class="[
              'py-1 px-3.5 border-2 cursor-pointer text-[0.82rem] sm:hidden transition font-semibold',
              calViewMode === 'agenda'
                ? 'border-neongreen text-white'
                : 'border-neonred text-zinc-300 hover:text-white'
            ]"
            @click="calViewMode = 'agenda'"
          >Uge</button>
          <button
            type="button"
            :class="[
              'py-1 px-3.5 border-2 cursor-pointer text-[0.82rem] hidden sm:block transition font-semibold',
              calViewMode === 'week'
                ? 'border-neongreen text-white'
                : 'border-neonred text-zinc-300 hover:text-white'
            ]"
            @click="calViewMode = 'week'"
          >Uge</button>
          <button
            type="button"
            :class="[
              'py-1 px-3.5 border-2 cursor-pointer text-[0.82rem] transition font-semibold',
              calViewMode === 'month'
                ? 'border-neongreen text-white'
                : 'border-neonred text-zinc-300 hover:text-white'
            ]"
            @click="calViewMode = 'month'"
          >Måned</button>
        </div>
      </div>

      <!-- Day / Week grid -->
      <div v-if="calViewMode === 'day' || calViewMode === 'week'" class="border border-neonred/40 overflow-hidden text-[0.8rem]">
        <div class="overflow-y-auto max-h-151 bg-black" ref="calScrollContainer">
          <!-- header -->
          <div class="grid border-b border-zinc-700 sticky top-0 z-10" :style="{ gridTemplateColumns: `60px repeat(${calDisplayDays.length}, 1fr)` }">
            <div class="min-h-11 bg-black border-r border-zinc-700"></div>
            <div
              v-for="day in calDisplayDays"
              :key="day.key"
              class="flex flex-col items-center justify-center py-1.5 px-1 gap-0.5 border-r border-zinc-700 transition-colors duration-100"
              :class="[
                day.isToday ? 'bg-neonred/10' : day.isPast ? 'bg-zinc-800' : 'bg-black',
                'cursor-pointer hover:bg-zinc-700'
              ]"
              @click="selectCalDay(day.key)"
            >
              <span
                class="text-[0.68rem] font-bold uppercase tracking-[0.05em]"
                :class="day.isPast ? 'text-zinc-600' : 'text-zinc-400'"
              >{{ day.name }}</span>
              <span
                class="w-6.5 h-6.5 flex items-center justify-center rounded-full text-[0.95rem] font-semibold"
                :class="day.isSelected ? 'bg-neonred text-white' : day.isPast ? 'text-zinc-600' : 'text-zinc-200'"
              >{{ day.num }}</span>
            </div>
          </div>

          <div class="grid" :style="{ gridTemplateColumns: `60px repeat(${calDisplayDays.length}, 1fr)` }">
            <!-- Time gutter -->
            <div class="bg-black border-r border-zinc-700">
              <div
                v-for="hour in CAL_HOURS"
                :key="hour"
                class="h-13 box-border text-right pr-1.5 pt-1 text-zinc-500 text-[0.68rem] leading-none border-b border-zinc-700"
              >{{ hour }}</div>
            </div>

            <!-- Day columns -->
            <div
              v-for="day in calDisplayDays"
              :key="day.key"
              class="relative border-r border-zinc-700"
              :class="{ 'bg-zinc-800/50': day.isPast }"
            >
              <div v-for="hour in CAL_HOURS" :key="hour" class="h-13 box-border border-b border-zinc-700/60"></div>
              <div
                v-for="slot in (calSlotsByDate[day.key] || [])"
                :key="slot.id"
                :class="[
                  'absolute left-0.5 right-0.5 box-border flex cursor-pointer transition-[filter] duration-150 overflow-hidden hover:brightness-[1.08]',
                  (slot.duration_min ?? 60) <= 30
                    ? 'items-center px-1.5 py-0'
                    : 'flex-col justify-start px-1.5 py-1 gap-0.5',
                  slot._type === 'window' ? 'z-0 text-neongreen' : (slot.is_booked ? 'z-1 text-white' : 'z-1 text-black')
                ]"
                :style="{ ...calBookingStyle(slot.slot_time, slot.duration_min ?? 60), background: calSlotColor(slot), opacity: day.isPast ? 0.35 : (slot._type === 'window' ? 0.55 : 1) }"
                @click.stop="$emit('open-slot', slot)"
                @mouseenter="(e) => showCalTooltip(slot, e)"
                @mouseleave="hideCalTooltip"
              >
                <!-- Short slot (≤ 30 min): single condensed line -->
                <template v-if="(slot.duration_min ?? 60) <= 30">
                  <span class="text-[0.63rem] font-bold leading-none truncate shrink-0">{{ slot.slot_time.slice(0,5) }}</span>
                  <span class="text-[0.63rem] leading-none mx-0.5 opacity-60 shrink-0">·</span>
                  <span class="text-[0.63rem] leading-none truncate opacity-90">{{ slot._type === 'window' ? 'Tilgængelig' : (slot.is_booked ? (slot.name || 'Booket') : 'Ledig') }}</span>
                </template>
                <!-- Normal slot -->
                <template v-else>
                  <div class="font-bold text-[0.72rem] leading-tight truncate">{{ slot._type === 'window' ? 'Tilgængelig' : (slot.is_booked ? (slot.name || 'Booket') : 'Ledig') }}</div>
                  <div class="text-[0.65rem] opacity-90 truncate">{{ slot.slot_time.slice(0,5) }}–{{ calSlotEndTime(slot.slot_time, slot.duration_min ?? 60) }}</div>
                  <div v-if="slot.is_booked && slot.participants && (slot.duration_min ?? 60) > 45" class="text-[0.65rem] opacity-90 truncate">{{ slot.participants }} pers.</div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Month view -->
      <div v-if="calViewMode === 'month'" class="border border-neonred/40 overflow-hidden text-[0.8rem] bg-black">
        <div class="grid grid-cols-7 border-b border-zinc-700 bg-zinc-800">
          <div
            v-for="name in CAL_WEEKDAY_NAMES"
            :key="name"
            class="py-1.5 text-center text-[0.65rem] font-bold uppercase tracking-wide text-neonred"
          >{{ name }}</div>
        </div>
        <div class="grid grid-cols-7">
          <div
            v-for="cell in calMonthCells"
            :key="cell.key"
            class="min-h-14 sm:min-h-18 border-b border-r border-zinc-700/60 p-1 flex flex-col"
            :class="[
              cell.outside ? 'bg-zinc-800' : cell.isPast ? 'bg-zinc-800 cursor-pointer hover:bg-zinc-700' : 'cursor-pointer hover:bg-zinc-700 active:bg-zinc-600',
            ]"
            @click="!cell.outside && onMonthDayClick(cell)"
          >
            <span
              class="self-center w-6.5 h-6.5 flex items-center justify-center rounded-full text-[0.8rem] font-semibold"
              :class="[
                cell.isSelected && !cell.outside ? 'bg-neonred text-white' :
                cell.isToday ? 'bg-neonred/20 text-neonred/80 font-bold' :
                cell.outside || cell.isPast ? 'text-zinc-600' : 'text-zinc-200'
              ]"
            >{{ cell.num }}</span>
            <div class="flex flex-wrap gap-0.5 justify-center mt-1">
              <span
                v-for="(slot, i) in cell.slots.slice(0, 3)"
                :key="i"
                class="w-1.5 h-1.5 rounded-full"
                :style="{ background: calSlotColor(slot), opacity: cell.isPast ? 0.45 : 1 }"
              ></span>
              <span v-if="cell.slots.length > 3" class="text-[0.58rem] text-gray-400 leading-none self-center">+{{ cell.slots.length - 3 }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Agenda view -->
      <div v-if="calViewMode === 'agenda'" class="border border-neonred/40 overflow-hidden text-[0.8rem] bg-black">
        <!-- Scrollable date strip -->
        <div class="flex overflow-x-auto gap-1 px-2 py-2 border-b border-zinc-700 bg-zinc-800">
          <button
            v-for="d in calAgendaDays"
            :key="d.key"
            class="flex flex-col items-center justify-center min-w-10 h-13 px-2 shrink-0 gap-0.5 cursor-pointer transition-colors duration-100"
            :class="d.key === selectedCalDate ? 'bg-neonred text-white' : d.isToday ? 'bg-neonred/15 text-neonred/70 font-bold' : 'text-zinc-300 hover:bg-zinc-700'"
            @click="selectCalDay(d.key)"
          >
            <span class="text-[0.6rem] font-bold uppercase tracking-wide">{{ d.name }}</span>
            <span class="text-[0.9rem] font-semibold leading-tight">{{ d.num }}</span>
          </button>
        </div>
        <!-- Slot list -->
        <div class="divide-y divide-zinc-700">
          <div v-if="!calAgendaSlots.length" class="py-10 text-center text-zinc-500 text-[0.85rem]">Ingen tider denne dag</div>
          <div
            v-for="slot in calAgendaSlots"
            :key="slot.id"
            class="px-3 py-3 cursor-pointer hover:bg-zinc-700 transition-colors"
            :class="slot.is_booked ? 'bg-neonred/10 hover:bg-neonred/15' : ''"
            @click="$emit('open-slot', slot)"
          >
            <div class="flex items-center gap-3">
              <div class="w-1 self-stretch rounded-full shrink-0" :style="{ background: calSlotColor(slot) }"></div>
              <div class="flex-1 min-w-0">
                <span class="font-semibold text-zinc-200">kl. {{ slot.slot_time.slice(0,5) }}–{{ calSlotEndTime(slot.slot_time, slot.duration_min ?? 60) }}</span>
                <span :class="slot.is_booked ? 'text-neonred' : 'text-neongreen'" class="ml-2 text-xs font-medium">
                  {{ slot.is_booked ? 'Booket' : 'Ledig' }}
                </span>
                <span v-if="slot.is_booked && slot.name" class="ml-2 text-xs text-zinc-400 truncate">{{ slot.name }}</span>
              </div>
              <svg class="w-4 h-4 text-zinc-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Selected day detail panel (day / week / month views) -->
      <div v-if="selectedCalDate && calViewMode !== 'agenda'" class="mt-5">
        <h3 class="font-medium mb-3 text-zinc-300">{{ formatDate(selectedCalDate) }}</h3>
        <p v-if="slotsForSelectedDate.length === 0" class="text-sm text-zinc-500">Ingen tider denne dag.</p>
        <ul v-else class="divide-y divide-zinc-700 border border-neonred/40 overflow-hidden mb-4">
          <li
            v-for="slot in slotsForSelectedDate"
            :key="slot.id"
            class="px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-zinc-700 transition-colors"
            :class="slot.is_booked ? 'bg-neonred/10 hover:bg-neonred/15' : ''"
            @click="$emit('open-slot', slot)"
          >
            <div>
              <span class="font-medium text-zinc-200">kl. {{ slot.slot_time.slice(0, 5) }}–{{ calSlotEndTime(slot.slot_time, slot.duration_min ?? 60) }}</span>
              <span
                :class="slot.is_booked ? 'text-neonred' : 'text-neongreen'"
                class="ml-3 text-sm font-medium"
              >{{ slot.is_booked ? 'Booket' : 'Ledig' }}</span>
              <span v-if="slot.is_booked && slot.name" class="ml-2 text-sm text-zinc-400">· {{ slot.name }}</span>
            </div>
            <svg class="w-4 h-4 text-zinc-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </li>
        </ul>
      </div>

      <BookingAdminTooltip :tooltip-slot="calTooltipSlot" :tooltip-style="calTooltipStyle" />

    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  slots:        { type: Array,   required: true },
  today:        { type: String,  required: true },
  slotsLoading: { type: Boolean, default: false },
  loadError:    { type: String,  default: '' },
})

const emit = defineEmits(['refresh', 'delete-slot', 'cancel-booking', 'select-date', 'open-slot'])

// tidssøjlen vises fra 07:00 til 21:00
const CAL_HOURS = Array.from({ length: 15 }, (_, i) => `${String(i + 7).padStart(2, '0')}:00`)
const CAL_START_MIN = 7 * 60  // offset til CSS top-beregning
const CAL_HOUR_PX = 52        // højde per time i pixels
// Ugedagsnavne fra mandag – 2024-01-01 er anchor fordi det er en mandag
const CAL_WEEKDAY_NAMES = Array.from({ length: 7 }, (_, i) => {
  const d = new Date(2024, 0, 1 + i)
  return d.toLocaleDateString('da-DK', { weekday: 'short' }).replace(/\.$/, '')
})

// Omregner starttid og varighed til CSS top/height i tidskolonnen
function calBookingStyle(slotTime, durationMin = 60) {
  const [h, m] = slotTime.slice(0, 5).split(':').map(Number)
  const top    = (h * 60 + m - CAL_START_MIN) / 60 * CAL_HOUR_PX
  const height = durationMin / 60 * CAL_HOUR_PX
  return { top: `${top}px`, height: `${height}px` }
}

const calViewMode = ref('week')
const calCurrentDate = ref(new Date())
const calScrollContainer = ref(null)
const selectedCalDate = ref('')

const calTodayStart = computed(() => {
  const d = new Date(); d.setHours(0,0,0,0); return d
})

// Mandagen i den aktuelle uge – getDay() returnerer 0 for søndag, så søndag justeres med -6
const calWeekStart = computed(() => {
  const d = new Date(calCurrentDate.value)
  d.setHours(0,0,0,0)
  const day = d.getDay()
  d.setDate(d.getDate() + (day === 0 ? -6 : 1 - day))
  return d
})

// navigerer én dag, uge eller måned frem/tilbage afhængig af aktiv visning
function calNavigate(dir) {
  const d = new Date(calCurrentDate.value)
  if      (calViewMode.value === 'month') d.setMonth(d.getMonth() + dir)
  else if (calViewMode.value === 'day')   d.setDate(d.getDate() + dir)
  else                                    d.setDate(d.getDate() + dir * 7)
  calCurrentDate.value = d
}

function calGoToToday() { calCurrentDate.value = new Date() } // hopper til dags dato

const calDisplayDays = computed(() => {
  const today = calTodayStart.value
  if (calViewMode.value === 'day') {
    const d = new Date(calCurrentDate.value); d.setHours(0,0,0,0)
    const key = calToKey(d)
    return [{ key, name: d.toLocaleDateString('da-DK', { weekday: 'short' }), num: d.getDate(),
              isToday: d.getTime() === today.getTime(), isPast: d < today, isSelected: key === selectedCalDate.value }]
  }
  return Array.from({ length: 7 }, (_, i) => {
    const d = new Date(calWeekStart.value)
    d.setDate(d.getDate() + i)
    const key = calToKey(d)
    return { key, name: d.toLocaleDateString('da-DK', { weekday: 'short' }), num: d.getDate(),
             isToday: d.getTime() === today.getTime(), isPast: d < today, isSelected: key === selectedCalDate.value }
  })
})

// tekst i toolbar-headeren, fx "28. apr – 4. maj 2026"
const calRangeLabel = computed(() => {
  if (calViewMode.value === 'month')
    return calCurrentDate.value.toLocaleDateString('da-DK', { month: 'long', year: 'numeric' })
  if (calViewMode.value === 'day')
    return new Date(calCurrentDate.value).toLocaleDateString('da-DK', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
  const end = new Date(calWeekStart.value); end.setDate(end.getDate() + 6)
  return `${calWeekStart.value.toLocaleDateString('da-DK', { day: 'numeric', month: 'short' })} – ${end.toLocaleDateString('da-DK', { day: 'numeric', month: 'short', year: 'numeric' })}`
})

const calSlotsByDate = computed(() => {
  const result = {}
  for (const slot of props.slots) {
    if (!result[slot.slot_date]) result[slot.slot_date] = []
    result[slot.slot_date].push(slot)
  }
  return result
})

const calMonthCells = computed(() => {
  const today  = calTodayStart.value
  const year   = calCurrentDate.value.getFullYear()
  const month  = calCurrentDate.value.getMonth()
  const first  = new Date(year, month, 1)
  const offset = (first.getDay() || 7) - 1
  return Array.from({ length: 42 }, (_, i) => {
    const d   = new Date(year, month, 1 - offset + i)
    const key = calToKey(d)
    const outside = d.getMonth() !== month
    return {
      key, num: d.getDate(), outside,
      isToday:    d.getTime() === today.getTime(),
      isPast:     d < today,
      isSelected: key === selectedCalDate.value,
      slots: outside ? [] : (calSlotsByDate.value[key] || []),
    }
  })
})

const calAgendaDays = computed(() =>
  Array.from({ length: 14 }, (_, i) => {
    const d = new Date(calWeekStart.value)
    d.setDate(d.getDate() + i)
    const key = calToKey(d)
    return { key, name: d.toLocaleDateString('da-DK', { weekday: 'short' }), num: d.getDate(),
             isToday: d.getTime() === calTodayStart.value.getTime() }
  })
)

// slots for den valgte dag , sorteret efter tid
const calAgendaSlots = computed(() =>
  (calSlotsByDate.value[selectedCalDate.value] || []).slice().sort((a, b) => a.slot_time.localeCompare(b.slot_time))
)

// samme som calAgendaSlots men bruges i dag/uge/måned-visningens detailpanel
const slotsForSelectedDate = computed(() =>
  (calSlotsByDate.value[selectedCalDate.value] || []).slice().sort((a, b) => a.slot_time.localeCompare(b.slot_time))
)

// vælger en dag og emitter select-date til parent
function selectCalDay(dayKey) {
  selectedCalDate.value = dayKey
  if (dayKey >= props.today) emit('select-date', dayKey)
}

function onMonthDayClick(cell) {
  selectCalDay(cell.key)
  calCurrentDate.value = new Date(cell.key + 'T00:00:00')
}

// eksponeret til BookingerTab så den kan hoppe til en dato efter gem
function navigateTo(dateStr, select = false) {
  calCurrentDate.value = new Date(dateStr + 'T00:00:00')
  if (select) selectedCalDate.value = dateStr
}

defineExpose({ navigateTo })

// Tooltip-state og positionsberegning her fordi adgang til de hoverede elementer.
// Selve renderingen i Tooltip.vue
const calTooltipSlot  = ref(null)
const calTooltipStyle = ref({})

function showCalTooltip(slot, event) {
  calTooltipSlot.value = slot
  const rect = event.currentTarget.getBoundingClientRect()
  const w    = 208
  const left = (window.innerWidth - rect.right) > w + 12 ? rect.right + 8 : rect.left - w - 8
  calTooltipStyle.value = { top: `${Math.min(rect.top, window.innerHeight - 140)}px`, left: `${left}px` }
}
function hideCalTooltip() { calTooltipSlot.value = null }

</script>