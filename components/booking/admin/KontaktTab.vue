<template>
  <div class="bg-black border-neon-subtle-neonred p-6 mb-2">
    <h2 class="font-black text-lg mb-4 tracking-wide text-white">Opdater kontaktoplysninger</h2>
    <form @submit.prevent="saveSettings" class="flex flex-col gap-3 max-w-sm">
      <div>
        <label class="block text-sm font-medium mb-1 text-white">CVR</label>
        <input v-model="settingsCvr" type="text" maxlength="20" class="border-neon-subtle-neonred px-3 py-2 w-full focus:outline-none text-sm bg-black text-white" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Email</label>
        <input v-model="settingsEmail" type="email" maxlength="200" class="border-neon-subtle-neonred px-3 py-2 w-full focus:outline-none text-sm bg-black text-white" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-white">Telefon</label>
        <input v-model="settingsPhone" type="text" maxlength="30" class="border-neon-subtle-neonred px-3 py-2 w-full focus:outline-none text-sm bg-black text-white" />
      </div>
      <button
        type="submit"
        :disabled="settingsSaving"
        class="bg-black text-white border-neon-subtle-neonred px-5 py-2 font-bold tracking-wide hover:border-neon-subtle-neongreen disabled:opacity-50 cursor-pointer self-start transition"
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
const emit = defineEmits(['update-contact-info', 'unauthorized', 'dirty-change'])

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl

const settingsCvr = ref('')
const settingsEmail = ref('')
const settingsPhone = ref('')
const settingsSaving = ref(false)
const settingsSaveError = ref('')
const settingsSaveInfo = ref('')
const kontaktLoaded = ref(false)

watch(
  [settingsCvr, settingsEmail, settingsPhone],
  () => { if (kontaktLoaded.value) emit('dirty-change', true) }
)

async function loadSettings() {
  try {
    const data = await $fetch(`${apiUrl}/settings.php`)
    settingsCvr.value     = data.cvr     ?? ''
    settingsEmail.value   = data.email   ?? ''
    settingsPhone.value   = data.phone   ?? ''
    emit('update-contact-info', { ...data })
    await nextTick()
    kontaktLoaded.value = true
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
        email: settingsEmail.value,
        phone: settingsPhone.value,
      },
    })
    settingsSaveInfo.value = 'Gemt!'
    emit('dirty-change', false)
    // Henter data igen efter gem, så formularen viser præcis det serveren har gemt
    const data = await $fetch(`${apiUrl}/settings.php`)
    settingsCvr.value     = data.cvr     ?? ''
    settingsEmail.value   = data.email   ?? ''
    settingsPhone.value   = data.phone   ?? ''
    emit('update-contact-info', { ...data })
  } catch (e) {
    if (e.status === 401) { emit('unauthorized'); return }
    settingsSaveError.value = e.data?.error ?? 'Kunne ikke gemme.'
  } finally {
    settingsSaving.value = false
  }
}

onMounted(() => {
  if (props.authed) loadSettings()
})
</script>
