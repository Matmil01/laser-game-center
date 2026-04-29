// Fetcher alt fra settings.php (kontakt info men også aktuelt fields)
export const useContactInfo = () => {
  const contact = useState('contactInfo', () => ({}))

  async function fetchContactInfo() {
    await callOnce('contactInfo', async () => {
      const config = useRuntimeConfig()
      try {
        contact.value = await $fetch(`${config.public.apiUrl}/settings.php`)
      } catch {
        contact.value = {}
      }
    })
  }

  return { contact, fetchContactInfo }
}