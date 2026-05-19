// Læser priser fra settings (price_1–price_4, price_eur_1–price_eur_4)
// locale-opmærksom formatering. Falder tilbage på hardcodede værdier som er de oprindelige priser

export const FALLBACK_DKK = [60, 100, 130, 160]
export const FALLBACK_EUR = [8, 14, 18, 22]

export const usePrices = () => {
  const { settings } = useSettings()

  function dkk(numGames) {
    // numGames (1–4)
    const raw = settings.value?.[`price_${numGames}`]
    const n   = parseFloat(raw)
    return Number.isFinite(n) && n > 0 ? n : FALLBACK_DKK[numGames - 1]
  }

  function eur(numGames) {
    const raw = settings.value?.[`price_eur_${numGames}`]
    const n   = parseFloat(raw)
    return Number.isFinite(n) && n > 0 ? n : FALLBACK_EUR[numGames - 1]
  }

  function formatted(numGames, locale) {
    const lang = locale ?? 'da'
    if (lang === 'da') {
      return `${dkk(numGames)} kr`
    }
    return `€${eur(numGames)}`
  }

  return { formatted }
}
