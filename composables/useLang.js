import { ref } from 'vue'

const lang = ref('da')

export function useLang() {
  return { lang }
}
