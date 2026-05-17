<template>
    <div id="kontakt" class="md:flex md:flex-row gap-8 p-8">
        <!-- Kontakt os -->
        <div class="p-6 border-neon-neonred md:w-6/12">
            <h2 class="text-white text-2xl font-bold text-left">
                {{ $t('kontakt.title') }}
            </h2>
            <div class="flex flex-col md:h-9/12 h-60 justify-between items-start py-14 md:pl-12">
                <div class="flex text-base md:text-xl items-center text-white min-w-0">
                    <img
                        src="/icons/telefon.svg"
                        :alt="$t('kontakt.phoneAlt')"
                        class="w-6 h-6 mr-4 shrink-0"
                    />
                    <a
                        v-if="settings.phone"
                        :href="`tel:${settings.phone.replace(/\s/g, '')}`"
                        class="hover:underline truncate"
                        >{{ settings.phone }}</a
                    >
                </div>

                <div class="flex text-base md:text-xl items-center text-white min-w-0">
                    <img
                        src="/icons/mail.svg"
                        :alt="$t('kontakt.mailAlt')"
                        class="w-6 h-6 mr-4 shrink-0"
                    />
                    <a
                        v-if="settings.email"
                        :href="`mailto:${settings.email}`"
                        class="hover:underline truncate"
                        >{{ settings.email }}</a
                    >
                </div>
                <div class="flex text-base md:text-xl items-center text-white min-w-0">
                    <img
                        src="/icons/lokation.svg"
                        :alt="$t('kontakt.addressAlt')"
                        class="w-6 h-6 mr-4 shrink-0"
                    />
                    <span class="text-white text-center truncate">Mønstervej 60, 6854 Henne</span>
                </div>
            </div>
            <div class="flex space-x-4 items-center justify-center w-full">
                <NeonButton
                    to="https://maps.app.goo.gl/qDZTcvpdaMJsfkLv9"
                    :text="$t('kontakt.googleMaps')"
                    customClass="text-base"
                />
                <NeonButton
                    to="https://maps.apple/p/AWPbK8TbMZgfJM"
                    :text="$t('kontakt.appleMaps')"
                    customClass="text-base"
                />
            </div>
        </div>
        <!-- Maps -->
        <div
            class="border-neon-neonred md:p-6 mt-6 md:mt-0 box-border md:w-6/12"
        >
            <img
                src="/icons/lasergamecenter-i-danmark.svg"
                alt="Kort over lokation"
                class="w-full h-96"
            />

        </div>
    </div>
</template>

<script setup>
const { settings, fetchSettings } = useSettings();
onMounted(fetchSettings);
</script>
