export const useContactInfo = () => {
  const contact = useState('contactInfo', () => null)

  async function fetchContactInfo() {
    if (contact.value !== null) return
    const config = useRuntimeConfig()
    try {
      contact.value = await $fetch(`${config.public.apiUrl}/settings.php`)
    } catch {
      contact.value = {}
    }
  }

  return { contact, fetchContactInfo }
}
