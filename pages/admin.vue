<template>
  <div>
    <BookingNav />
  <div class="max-w-5xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-black mb-8 tracking-wide text-white">Admin</h1>

    <!-- Login -->
    <AdminLogin
      v-if="!authed"
      :passwordInput="passwordInput"
      :loginError="loginError"
      :loginLoading="loginLoading"
      @update:passwordInput="val => passwordInput = val"
      @login="login"
    />

    <!-- Admin-panel med tabs -->
    <template v-else>
      <div class="flex border-b-2 border-neonred mb-8">
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'booking' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white'"
          @click="tryChangeTab('booking')"
        >Bookinger</button>
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'aktuelt' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white'"
          @click="tryChangeTab('aktuelt')"
        >Aktuelt</button>
        <button
          class="px-4 py-2 font-bold text-sm border-b-2 transition-colors"
          :class="activeTab === 'contact' ? 'border-neonred text-white' : 'border-transparent text-zinc-400 hover:text-white'"
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
          @update-aktuelt-info="onContactInfoUpdated"
          @unauthorized="handleUnauthorized"
          @dirty-change="v => dirtyTabs.aktuelt = v"
        />
      </template>
      <template v-if="activeTab === 'contact'">
        <KontaktTab
          :password="password"
          :authed="authed"
          @update-contact-info="onContactInfoUpdated"
          @unauthorized="handleUnauthorized"
          @dirty-change="v => dirtyTabs.contact = v"
        />
      </template>
    </template>
  </div>
  </div>

</template>

<script setup>
import BookingNav from '~/components/booking/BookingNav.vue'
import AdminLogin from '~/components/booking/AdminLogin.vue'
import BookingerTab from '~/components/booking/admin/BookingerTab.vue'
import KontaktTab from '~/components/booking/admin/KontaktTab.vue'
import AktueltTab from '~/components/booking/admin/AktueltTab.vue'

const config  = useRuntimeConfig()
const apiUrl  = config.public.apiUrl
const { contact: contactInfo } = useContactInfo()

// Når en fane gemmer indstillinger, opdaterer kontaktinfo med det samme –
// så footer og kontaktsiden ikke viser forældet data uden refresh.
function onContactInfoUpdated(val) {
  contactInfo.value = { ...(contactInfo.value ?? {}), ...val }
}
// just the localStorage key
const PASSWORD_KEY = 'admin_pw'

const passwordInput = ref('')
const loginError    = ref('')
const loginLoading  = ref(false)
const authed        = ref(false)
const password      = ref('')

const activeTab    = ref('booking')
const dirtyTabs    = reactive({ aktuelt: false, contact: false })

function tryChangeTab(tab) {
  const currentDirty = dirtyTabs[activeTab.value]
  if (currentDirty && !window.confirm('Du har ændringer der ikke er gemt. Vil du forlade fanen?')) return
  dirtyTabs[activeTab.value] = false
  activeTab.value = tab
}

// Henter gemt adgangskode fra localStorage, så admin ikke skal logge ind igen ved sideopdatering.
onMounted(() => {
  const stored = localStorage.getItem(PASSWORD_KEY)
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
    localStorage.setItem(PASSWORD_KEY, passwordInput.value)
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
  localStorage.removeItem(PASSWORD_KEY)
  password.value = ''
  authed.value = false
}

function handleUnauthorized() {
  logout()
  loginError.value = 'Session udløbet – log ind igen.'
}
</script>