<template>
  <form @submit.prevent="emit('login')" class="space-y-4 max-w-xs">
    <div>
      <label class="block text-sm font-medium mb-1 cursor-pointer text-white">Adgangskode</label>
      <input
        :value="passwordInput"
        @input="emit('update:passwordInput', $event.target.value)"
        type="password"
        required
        class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white cursor-pointer"
      />
    </div>
    <p v-if="loginError" class="text-sm text-neonred">{{ loginError }}</p>
    <button
      type="submit"
      :disabled="loginLoading"
      class="w-full bg-black text-white border-neon-subtle-neonred py-2 font-bold tracking-wide hover:border-neon-subtle-neongreen disabled:opacity-50 cursor-pointer transition"
    >
      {{ loginLoading ? 'Checker…' : 'Log ind' }}
    </button>
  </form>
</template>

<script setup>
// Simpel login-formular til admin.
// selve auth (API-kald, fejlhåndtering, tilstandsstyring) håndteres i parent.

const props = defineProps({
  passwordInput: { type: String,  required: true },
  loginError:    { type: String,  required: true },
  loginLoading:  { type: Boolean, required: true }
})

const emit = defineEmits(['update:passwordInput', 'login'])
</script>
