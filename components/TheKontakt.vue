<template>
    <div id="kontakt" class="md:flex md:flex-row gap-8 p-8">
        <!-- Kontakt os -->
        <div class="flex-1 p-6 border-neon-neonred">
            <h2 class="text-white text-2xl font-bold text-left">
                Kontakt os
            </h2>
            <div class="flex flex-col md:h-9/12 h-60 justify-between items-start py-14 md:pl-12">
                <div class="flex text-xl items-center text-white">
                    <img
                        src="/icons/telefon.svg"
                        alt="Telefon ikon"
                        class="w-6 h-6 mr-4"
                    />
                    <a
                        v-if="contact.phone"
                        :href="`tel:${contact.phone.replace(/\s/g, '')}`"
                        class="hover:underline"
                        >{{ contact.phone }}</a
                    >
                </div>

                <div class="flex text-xl items-center text-white">
                    <img
                        src="/icons/mail.svg"
                        alt="Mail ikon"
                        class="w-6 h-6 mr-4"
                    />
                    <a
                        v-if="contact.email"
                        :href="`mailto:${contact.email}`"
                        class="hover:underline"
                        >{{ contact.email }}</a
                    >
                </div>
                <div class="flex text-xl items-center text-white">
                    <img
                        src="/icons/lokation.svg"
                        alt="Adresse ikon"
                        class="w-6 h-6 mr-4"
                    />
                    <span class="text-white text-center">{{
                        contact.address
                    }}</span>
                </div>
            </div>
            <div class="flex space-x-4 items-center justify-center w-full">
                <NeonButton
                    to="https://maps.app.goo.gl/qDZTcvpdaMJsfkLv9"
                    text="Se på Google Maps"
                />
                <NeonButton
                    to="https://maps.apple/p/AWPbK8TbMZgfJM"
                    text="Se på Apple Maps"
                />
            </div>
        </div>
        <!-- Maps -->
        <div
            class="flex-1 border-neon-neonred md:p-6 mt-6 md:mt-0 box-border"
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
const { contact, fetchContactInfo } = useContactInfo();
onMounted(fetchContactInfo);
</script>
