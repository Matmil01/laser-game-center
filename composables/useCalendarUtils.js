// Timeslots og datoformatering bruges i både admin- og bookingkomponenter.
// Samme værdier og regler (dansk lokale, tidszone) så de er samlet her.
export const timeSlotOptions = Array.from({ length: 32 }, (_, i) => {
  const total = 7 * 60 + i * 30
  return `${String(Math.floor(total / 60)).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`
})

// sv-SE giver YYYY-MM-DD datoformat som er tidzone-uafhængigt
export function calToKey(date) {
  return date.toLocaleDateString('sv-SE')
}

// konverterer til dato med ugedag, fx "tir. 5. maj"
export function formatDate(dateStr) {
  return new Date(dateStr + 'T00:00:00').toLocaleDateString('da-DK', {
    weekday: 'short', day: 'numeric', month: 'short',
    timeZone: 'Europe/Copenhagen',
  })
}

// beregner sluttidspunkt ud fra starttid og varighed i minutter
export function calSlotEndTime(slotTime, durationMin = 60) {
  const [h, m] = slotTime.slice(0, 5).split(':').map(Number)
  const end = h * 60 + m + durationMin
  return `${String(Math.floor(end / 60)).padStart(2, '0')}:${String(end % 60).padStart(2, '0')}`
}

// farveskift ved ledig/optaget i kalender og tooltip
export function calSlotColor(slot) {
  if (slot._type === 'window') return 'var(--color-neongreen)'
  return slot.is_booked ? 'var(--color-neonred)' : 'var(--color-neongreen)'
}
