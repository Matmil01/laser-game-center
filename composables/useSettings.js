// Fetcher alle admin settings fra settings.php (bortset for priser, som håndteres i usePrices.js)
export const useSettings = () => {
  const settings = useState('settings', () => ({}))

  async function fetchSettings() {
    await callOnce('settings', async () => {
      const config = useRuntimeConfig()
      try {
        settings.value = await $fetch(`${config.public.apiUrl}/settings.php`)
      } catch {
        settings.value = {}
      }
    })
  }

  return { settings, fetchSettings }
}
