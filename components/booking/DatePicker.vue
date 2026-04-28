<template>
  <v-calendar
    class="booking-datepicker"
    locale="da"
    :first-day-of-week="2"
    :is-expanded="isExpanded"
    :min-date="minDate"
    :attributes="attributes"
    @dayclick="$emit('dayclick', $event)"
  />
</template>

<script setup>
defineProps({
  minDate:    { default: undefined },
  attributes: { type: Array, default: () => [] },
  isExpanded: { type: Boolean, default: false },
})
defineEmits(['dayclick'])
</script>

<style>
/* Bliver rendered bagved så har sin egen css
/* Chat ved tit hvilke selectors v-calendar bruger
/* Når den ikke gør skal den styles ved at inspect efter classes, ligesom vi gjorde med woocommerce.... yay */

.booking-datepicker.vc-container {
  --vc-gray-50:   #18181b;
  --vc-gray-100:  #27272a;
  --vc-gray-200:  #3f3f46;
  --vc-gray-300:  #52525b;
  --vc-gray-400:  #71717a;
  --vc-gray-500:  #a1a1aa;
  --vc-gray-600:  #d4d4d8;
  --vc-gray-700:  #e4e4e7;
  --vc-gray-800:  #f4f4f5;
  --vc-gray-900:  #fafafa;

  /* Explicit semantic overrides — these win over .vc-light's defaults on this element */
  --vc-bg:                         #000000;
  --vc-border:                     #3f3f46;
  --vc-rounded:                    0px;
  --vc-hover-bg:                   rgba(255, 255, 255, 0.1);
  --vc-header-arrow-color:         #ffffff;
  --vc-header-arrow-hover-bg:      rgba(255, 255, 255, 0.1);
  --vc-header-title-color:         #ffffff;
  --vc-weekday-color:              #71717a;
  --vc-day-content-hover-bg:       rgba(255, 255, 255, 0.1);
  --vc-day-content-disabled-color: #3f3f46;

  font-family:  inherit;
  color:        #ffffff;
  border-color: #3f3f46;
}

.booking-datepicker .vc-title {
  color:          #ffffff;
  font-weight:    700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* Header prev/next */
.booking-datepicker .vc-arrow {
  color:         #ffffff;
  border-radius: 0;
}

.booking-datepicker .vc-arrow:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.booking-datepicker .vc-nav-arrow {
  color: #ffffff;
}

.booking-datepicker .vc-nav-arrow:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.booking-datepicker .vc-weekday {
  color:     #71717a;
  font-size: 0.75rem;
}

.booking-datepicker .vc-day-content {
  color:         #ffffff;
  border-radius: 0;
}

.booking-datepicker .vc-day-content.vc-highlight-content-light {
  color: #18181b;
}

.booking-datepicker .vc-day-content:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.booking-datepicker .vc-day-content.vc-disabled {
  color:  #3f3f46;
  cursor: not-allowed;
}

.booking-datepicker .vc-nav-popover-container {
  background-color: #18181b;
  border-color:     #3f3f46;
  border-radius:    0;
}

.booking-datepicker .vc-nav-title,
.booking-datepicker .vc-nav-item {
  color: #e4e4e7;
}

.booking-datepicker .vc-nav-item:hover,
.booking-datepicker .vc-nav-title:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color:            #ffffff;
}

.booking-datepicker .vc-nav-item.is-active {
  background-color: var(--color-neonred);
  color:            #ffffff;
}

/* Highlight for available dates */
.booking-datepicker .vc-highlight-bg-light {
  background-color: var(--color-neongreen, #39ff14) !important;
}

/* Highlight for selected date */
.booking-datepicker .vc-highlight-bg-solid {
  background-color: var(--color-neonblue, #00bfff) !important;
}
</style>