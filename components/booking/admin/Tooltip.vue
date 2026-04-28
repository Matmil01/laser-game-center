<template>
    <!-- Tooltip, når man hover over et slot i AdminCalendar -->
  <Teleport to="body">
    <div
      v-if="tooltipSlot"
      class="fixed z-50 bg-black border border-neonred shadow-[0_0_8px_1px_var(--color-neonred)] p-3 w-52 pointer-events-none text-white"
      :style="tooltipStyle"
    >
      <div class="font-bold text-[0.88rem] mb-0.5">
        {{ tooltipSlot._type === 'window' ? 'Tilgængelig' : (tooltipSlot.is_booked ? (tooltipSlot.name || 'Booket') : 'Ledig') }}
      </div>
      <div v-if="tooltipSlot.is_booked && tooltipSlot.num_games" class="text-[0.85rem] font-semibold text-white mt-0.5">
        {{ tooltipSlot.num_games }} spil · {{ tooltipSlot.participants }} pers.
      </div>
      <div class="text-[0.82rem] text-zinc-400 mt-0.5">
        {{ tooltipSlot.slot_time.slice(0, 5) }}–{{ calSlotEndTime(tooltipSlot.slot_time, tooltipSlot.duration_min ?? 60) }}
      </div>
      <div v-if="tooltipSlot.is_booked && tooltipSlot.email" class="text-[0.82rem] text-zinc-400 mt-0.5">
        {{ tooltipSlot.email }}
      </div>
      <div class="mt-1.5">
        <span
          class="inline-block px-2 py-0.5 rounded-full text-[0.72rem] font-semibold"
          :class="tooltipSlot.is_booked ? 'text-white' : 'text-black'"
          :style="{ background: calSlotColor(tooltipSlot) }"
        >
          {{ tooltipSlot.is_booked ? 'Booket' : 'Ledig' }}
        </span>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({
  tooltipSlot:  { type: Object, default: null },
  tooltipStyle: { type: Object, default: () => ({}) },
})
</script>
