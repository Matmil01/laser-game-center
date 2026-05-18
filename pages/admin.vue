<template>
  <div>
  <div class="max-w-5xl mx-auto px-6 py-10">

    <!-- Login -->
    <div v-if="!authed" class="flex items-center justify-center" style="min-height: calc(100vh - 12rem);">
    <div>
      <h1 class="text-3xl font-black mb-8 tracking-wide text-white">Log ind</h1>
    <AdminLogin
      v-if="!authed"
      :passwordInput="passwordInput"
      :loginError="loginError"
      :loginLoading="loginLoading"
      @update:passwordInput="val => passwordInput = val"
      @login="login"
    />
    </div>
    </div>

    <!-- Admin-panel med tabs -->
    <template v-else>
      <h1 class="text-3xl font-black mb-8 tracking-wide text-white">Admin</h1>
      <div class="flex border-b-2 border-neonred mb-8">
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'booking' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white cursor-pointer'"
          @click="tryChangeTab('booking')"
        >Bookinger</button>
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'aktuelt' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white cursor-pointer'"
          @click="tryChangeTab('aktuelt')"
        >Aktuelt</button>
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'priser' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white cursor-pointer'"
          @click="tryChangeTab('priser')"
        >Priser</button>
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'contact' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white cursor-pointer'"
          @click="tryChangeTab('contact')"
        >Kontakt</button>
        <button
          class="ml-auto px-4 py-2 font-bold text-sm border-b-2 border-transparent text-neonred hover:text-neonred/70 cursor-pointer transition-colors"
          @click="logout"
        >Log ud</button>
      </div>
      <template v-if="activeTab === 'booking'">
        <BookingerTab
          :password="password"
          :authed="authed"
          @unauthorized="handleUnauthorized"
        />
      </template>
      <template v-if="activeTab === 'aktuelt'">
        <AktueltTab
          :password="password"
          :authed="authed"
          @update-aktuelt-info="onSettingsUpdated"
          @unauthorized="handleUnauthorized"
          @dirty-change="v => dirtyTabs.aktuelt = v"
        />
      </template>
      <template v-if="activeTab === 'priser'">
        <PriserTab
          :password="password"
          :authed="authed"
          @unauthorized="handleUnauthorized"
          @dirty-change="v => dirtyTabs.priser = v"
        />
      </template>
      <template v-if="activeTab === 'contact'">
        <KontaktTab
          :password="password"
          :authed="authed"
          @update-contact-info="onSettingsUpdated"
          @unauthorized="handleUnauthorized"
          @dirty-change="v => dirtyTabs.contact = v"
        />
      </template>
    </template>
  </div>
  </div>

</template>

<script setup>
useSeoMeta({
  title: 'Admin – Lasergame Center Oksbøl',
  robots: 'noindex, nofollow',
})

import AdminLogin from '~/components/booking/AdminLogin.vue'
import BookingerTab from '~/components/booking/admin/BookingerTab.vue'
import KontaktTab from '~/components/booking/admin/KontaktTab.vue'
import AktueltTab from '~/components/booking/admin/AktueltTab.vue'
import PriserTab from '~/components/booking/admin/PriserTab.vue'

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl
const { settings: contactInfo } = useSettings()

// Når en fane gemmer indstillinger, opdaterer kontaktinfo med det samme –
// så footer og kontaktsiden ikke viser forældet data uden refresh.
function onSettingsUpdated(val) {
  contactInfo.value = { ...(contactInfo.value ?? {}), ...val }
}
// sessionStorage key — cleared when the tab/browser closes (Måske skal det være localStorage i stedet)
const PASSWORD_KEY = 'admin_pw'

const passwordInput = ref('')
const loginError    = ref('')
const loginLoading  = ref(false)
const authed        = ref(false)
const password      = ref('')

const activeTab    = ref('booking')
const dirtyTabs    = reactive({ aktuelt: false, contact: false, priser: false })

function tryChangeTab(tab) {
  const currentDirty = dirtyTabs[activeTab.value]
  if (currentDirty && !window.confirm('Du har ændringer der ikke er gemt. Vil du forlade fanen?')) return
  dirtyTabs[activeTab.value] = false
  activeTab.value = tab
}

// Henter gemt adgangskode fra sessionStorage, så admin ikke skal logge ind igen ved sideopdatering.
// sessionStorage ryddes, når fanen/browseren lukkes.
onMounted(() => {
  const stored = sessionStorage.getItem(PASSWORD_KEY)
  if (stored) {
    password.value = stored
    authed.value = true
  }
})

// Login tjekkes ved API-kald 
// 401 betyder forkert kode; alt andet er en server- eller netværksfejl.
async function login() {
  loginLoading.value = true
  loginError.value   = ''
  try {
    await $fetch(`${apiUrl}/admin-slots.php`, {
      method: 'POST',
      body: { action: 'list', pw: passwordInput.value },
    })
    password.value = passwordInput.value
    sessionStorage.setItem(PASSWORD_KEY, passwordInput.value)
    authed.value = true
  } catch (e) {
    if (e.status === 401) {
      loginError.value = 'Forkert adgangskode.'
    } else {
      loginError.value = 'Login-fejl.'
    }
  } finally {
    loginLoading.value = false
  }
}

function logout() {
  sessionStorage.removeItem(PASSWORD_KEY)
  password.value = ''
  authed.value   = false
}

function handleUnauthorized() {
  logout()
  loginError.value = 'Session udløbet – log ind igen.'
}
</script>