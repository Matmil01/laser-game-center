<template>
  <NuxtLink 
    v-if="to"
    :to="to"
    class="neon-pulse-button border-neon-neonred hover:border-neon-neongreen px-6 py-2 text-white transition-all inline-block no-underline"
    :class="customClass"
  >
    <slot>{{ text }}</slot>
  </NuxtLink>
  <button 
    v-else
    class="neon-pulse-button border-neon-neonred hover:border-neon-neongreen px-6 py-2 text-white transition-all"
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

<style scoped>
@keyframes neonPulse {
  0%, 100% {
    box-shadow: 0 0 18px 3px #00FF00;
  }
  50% {
    box-shadow: 0 0 30px 8px #00FF00;
  }
}

.neon-pulse-button:hover {
  animation: neonPulse 1.5s ease-in-out infinite;
}
</style>
