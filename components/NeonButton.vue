<template>
  <NuxtLink
    v-if="to"
    :to="to"
    class="neon-pulse-button border-neon-neonred hover:border-neon-neongreen px-6 py-2 text-white transition-all duration-500 inline-block no-underline text-center"
    :class="customClass"
  >
    <slot>{{ text }}</slot>
  </NuxtLink>
  <button
    v-else
    class="neon-pulse-button border-neon-neonred hover:border-neon-neongreen px-6 py-2 text-white transition-all duration-500 text-center"
    :class="customClass"
    @click="handleClick"
  >
    <slot>{{ text }}</slot>
  </button>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    default: ''
  },
  customClass: {
    type: String,
    default: ''
  },
  to: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['click'])

const handleClick = () => {
  emit('click')
}
</script>

