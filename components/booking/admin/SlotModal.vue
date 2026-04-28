<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
      @click.self="$emit('close')"
    >
      <div class="bg-black border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] w-full max-w-md mx-4 p-6">

        <div class="flex items-start justify-between mb-5">
          <div>
            <h2 class="font-bold text-lg leading-tight mb-1 text-white">{{ formatDate(slot.slot_date) }}</h2>
            <div class="flex items-center gap-2">
              <span
                class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold"
                :class="slot.is_booked ? 'text-white' : 'text-zinc-900'"
                :style="{ background: slot.is_booked ? 'var(--color-neonred)' : 'var(--color-neongreen)' }"
              >{{ slot.is_booked ? 'Booket' : 'Ledig' }}</span>
            </div>
          </div>
          <button
            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-zinc-700 text-zinc-400 hover:text-white text-xl leading-none cursor-pointer transition-colors"
            @click="$emit('close')"
          >×</button>
        </div>

        <template v-if="!slot.is_booked">
          <div class="bg-zinc-800 p-4 mb-4 text-sm">
            <p class="text-[0.7rem] font-semibold uppercase tracking-wide text-zinc-500 mb-2">Nuværende tidsramme</p>
            <p class="font-semibold text-white text-base">kl. {{ slot.slot_time.slice(0, 5) }}–{{ slotEndTime }}</p>
          </div>

          <div class="mb-5">
            <p class="text-[0.7rem] font-semibold uppercase tracking-wide text-zinc-500 mb-3">Rediger tidsramme</p>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium mb-1">Fra</label>
                <select
                  v-model="editFrom"
                  class="border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white cursor-pointer w-full text-sm"
                >
                  <option v-for="t in timeSlotOptions" :key="t" :value="t">{{ t }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Til</label>
                <select
                  v-model="editTo"
                  class="border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white cursor-pointer w-full text-sm"
                >
                  <option v-for="t in editToOptions" :key="t" :value="t">{{ t }}</option>
                </select>
              </div>
            </div>
          </div>

          <p v-if="saveError" class="mb-3 text-sm text-neonred">{{ saveError }}</p>

          <div class="flex flex-col gap-2">
            <button
              class="w-full px-4 py-2.5 bg-black border-2 border-neongreen text-white text-sm font-semibold cursor-pointer transition hover:border-neongreen/70 disabled:opacity-50"
              :disabled="saving || !editHasChanges"
              @click="$emit('update-slot', { id: slot.id, from: editFrom, to: editTo })"
            >{{ saving ? 'Gemmer…' : 'Gem ændringer' }}</button>
            <template v-if="!confirmDelete">
              <button
                class="w-full px-4 py-2.5 border border-neonred/50 text-neonred hover:bg-neonred/15 text-sm cursor-pointer transition-colors"
                @click="confirmDelete = true"
              >Slet tidsramme</button>
            </template>
            <template v-else>
              <div class="flex gap-2">
                <button
                  class="flex-1 px-4 py-2.5 bg-neonred text-white text-sm cursor-pointer disabled:opacity-50 transition-colors hover:bg-neonred/80"
                  :disabled="deletingId === slot.id"
                  @click="$emit('delete-slot', slot.id)"
                >{{ deletingId === slot.id ? 'Sletter…' : 'Bekræft slet' }}</button>
                <button
                  class="px-3 py-2.5 border border-zinc-600 text-zinc-300 hover:bg-zinc-700 text-sm cursor-pointer transition-colors"
                  @click="confirmDelete = false"
                >Nej</button>
              </div>
            </template>
          </div>
        </template>

        <template v-else>
          <div class="bg-zinc-800 p-4 space-y-2.5 text-sm mb-5">
            <div class="flex gap-2">
              <span class="text-zinc-200 w-20 shrink-0">Tidspunkt</span>
              <span class="font-semibold text-white">kl. {{ slot.slot_time.slice(0, 5) }}–{{ slotEndTime }}</span>
            </div>
            <template v-if="slot.num_games">
              <div class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Spil</span>
                <span class="font-semibold text-white">{{ slot.num_games }} spil ({{ slot.num_games * 30 }} min)</span>
              </div>
            </template>
            <template v-if="slot.name">
              <div class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Navn</span>
                <span class="font-semibold text-white">{{ slot.name }}</span>
              </div>
              <div v-if="slot.participants" class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Deltagere</span>
                <span class="font-semibold text-white">{{ slot.participants }} personer</span>
              </div>
              <div class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Email</span>
                <span class="font-semibold text-white break-all">{{ slot.email }}</span>
              </div>
              <div class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Tlf.</span>
                <span class="font-semibold text-white">{{ slot.phone }}</span>
              </div>

              <div v-if="slot.note" class="flex gap-2">
                <span class="text-zinc-200 w-20 shrink-0">Note</span>
                <span class="italic text-zinc-300">"{{ slot.note }}"</span>
              </div>
            </template>
            <p v-else class="text-zinc-400 italic">Ingen booking-detaljer fundet</p>
          </div>

          <button
            :disabled="cancellingId === slot.id"
            class="w-full py-2.5 border border-neonred/50 text-neonred hover:bg-neonred/15 text-sm font-medium disabled:opacity-50 cursor-pointer transition-colors"
            @click="$emit('cancel-booking', slot.id)"
          >{{ cancellingId === slot.id ? 'Annullerer…' : 'Annuller booking' }}</button>
        </template>

      </div>
    </div>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  slot:         { type: Object,  required: true },
  deletingId:   { default: null },
  cancellingId: { default: null },
  saving:       { type: Boolean, default: false },
  saveError:    { type: String,  default: '' },
})

defineEmits(['close', 'delete-slot', 'cancel-booking', 'update-slot'])

const calcEndTime = (s) => {
  const [h, m] = s.slot_time.slice(0, 5).split(':').map(Number)
  const end = h * 60 + m + (s.duration_min ?? 60)
  return `${String(Math.floor(end / 60)).padStart(2, '0')}:${String(end % 60).padStart(2, '0')}`
}

const editFrom      = ref(props.slot.slot_time.slice(0, 5))
const editTo        = ref(calcEndTime(props.slot))
const confirmDelete = ref(false)

watch(() => props.slot, (s) => {
  editFrom.value      = s.slot_time.slice(0, 5)
  editTo.value        = calcEndTime(s)
  confirmDelete.value = false
})

const editToOptions = computed(() => {
  const [h, m] = editFrom.value.split(':').map(Number)
  const fromMin = h * 60 + m
  return timeSlotOptions.filter(t => {
    const [th, tm] = t.split(':').map(Number)
    return th * 60 + tm > fromMin
  })
})

const editHasChanges = computed(() =>
  editFrom.value !== props.slot.slot_time.slice(0, 5) ||
  editTo.value   !== calcEndTime(props.slot)
)

const slotEndTime = computed(() => calcEndTime(props.slot))
</script>