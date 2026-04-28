<template>
  <div class="space-y-4 pt-2 border-t border-zinc-700">
    <!-- Honeypot -->
    <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
      <label for="website">Website</label>
      <input
        id="website"
        v-model="form.website"
        type="text"
        name="website"
        tabindex="-1"
        autocomplete="off"
      />
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 cursor-pointer">Navn</label>
      <input
        v-model="form.name"
        type="text"
        required
        placeholder="Dit fulde navn"
        class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer"
      />
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 cursor-pointer">Email</label>
      <input
        v-model="form.email"
        type="email"
        required
        placeholder="din@mor.dk"
        class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer"
      />
    </div>
    <div>
      <label class="block text-sm font-medium mb-1 cursor-pointer">Telefon</label>
      <input
        v-model="form.phone"
        type="tel"
        required
        placeholder="Dit telefonnummer"
        class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 cursor-pointer"
      />
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Besked <span class="text-zinc-500 font-normal">(valgfri)</span></label>
      <textarea
        v-model="form.note"
        rows="3"
        placeholder="Skriv gerne hvad du ønsker hjælp til…"
        class="w-full border border-zinc-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neonred bg-zinc-800 text-white placeholder-zinc-500 resize-none"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  form: {
    type: Object,
    required: true
  }
})
</script>
