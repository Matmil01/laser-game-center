// Timeslots og datoformatering bruges i både admin- og bookingkomponenter.
// Samme værdier og regler (dansk lokale, tidszone) så de er samlet her.
export const timeSlotOptions = Array.from({ length: 32 }, (_, i) => {
  const total = 7 * 60 + i * 30
  return `${String(Math.floor(total / 60)).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`
})

export function calToKey(date) {
  return date.toLocaleDateString('sv-SE')
}

export function formatDate(dateStr) {
  return new Date(dateStr + 'T00:00:00').toLocaleDateString('da-DK', {
    weekday: 'short', day: 'numeric', month: 'short',
    timeZone: 'Europe/Copenhagen',
  })
}
