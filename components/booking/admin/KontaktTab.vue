<template>
  <div class="bg-black border-2 border-neonred shadow-[0_0_18px_2px_var(--color-neonred)] p-6 mb-2">
    <h2 class="font-black text-lg mb-4 tracking-wide text-white">Opdater kontaktoplysninger</h2>
    <form @submit.prevent="saveSettings" class="flex flex-col gap-3 max-w-sm">
      <div>
        <label class="block text-sm font-medium mb-1 text-white">CVR</label>
        <input v-model="settingsCvr" type="text" maxlength="20" class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Adresse</label>
        <input v-model="settingsAddress" type="text" maxlength="200" class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Email</label>
        <input v-model="settingsEmail" type="email" maxlength="200" class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Telefon</label>
        <input v-model="settingsPhone" type="text" maxlength="30" class="border border-zinc-600 px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-neonred text-sm bg-zinc-800 text-white" />
      </div>
      <button
        type="submit"
        :disabled="settingsSaving"
        class="bg-black text-white border-2 border-neonred shadow-[0_0_8px_1px_var(--color-neonred)] px-5 py-2 font-bold tracking-wide hover:border-neongreen hover:shadow-[0_0_8px_1px_var(--color-neongreen)] disabled:opacity-50 cursor-pointer self-start transition"
      >
        {{ settingsSaving ? 'Gemmer…' : 'Gem' }}
      </button>
      <p v-if="settingsSaveInfo" class="text-sm text-neongreen">{{ settingsSaveInfo }}</p>
      <p v-if="settingsSaveError" class="text-sm text-neonred">{{ settingsSaveError }}</p>
    </form>
  </div>
</template>

<script setup>
const props = defineProps({
  password: String,
  authed: Boolean
})
const emit = defineEmits(['update-contact-info'])

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl

const settingsCvr = ref('')
const settingsAddress = ref('')
const settingsEmail = ref('')
const settingsPhone = ref('')
const settingsSaving = ref(false)
const settingsSaveError = ref('')
const settingsSaveInfo = ref('')

async function loadSettings() {
  try {
    const data = await $fetch(`${apiUrl}/settings.php`)
    settingsCvr.value     = data.cvr     ?? ''
    settingsAddress.value = data.address ?? ''
    settingsEmail.value   = data.email   ?? ''
    settingsPhone.value   = data.phone   ?? ''
    emit('update-contact-info', { ...data })
  } catch {}
}

async function saveSettings() {
  settingsSaving.value    = true
  settingsSaveError.value = ''
  settingsSaveInfo.value  = ''
  try {
    await $fetch(`${apiUrl}/settings.php`, {
      method: 'POST',
      body: {
        pw: props.password,
        cvr: settingsCvr.value,
        address: settingsAddress.value,
        email: settingsEmail.value,
        phone: settingsPhone.value,
      },
    })
    settingsSaveInfo.value = 'Gemt!'
    // Henter data igen efter gem, så formularen viser præcis det serveren har gemt
    const data = await $fetch(`${apiUrl}/settings.php`)
    settingsCvr.value     = data.cvr     ?? ''
    settingsAddress.value = data.address ?? ''
    settingsEmail.value   = data.email   ?? ''
    settingsPhone.value   = data.phone   ?? ''
    emit('update-contact-info', { ...data })
  } catch (e) {
    settingsSaveError.value = e.data?.error ?? 'Kunne ikke gemme.'
  } finally {
    settingsSaving.value = false
  }
}

onMounted(() => {
  if (props.authed) loadSettings()
})
</script>
