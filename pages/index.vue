<template>
<div class="bg-black min-h-screen justify-center">
    <div class="mt-20 sm:h-[570px] h-90">
        <div
            class="absolute mt-15 top-0 left-0 w-full sm:h-[570px] h-90 overflow-hidden z-0"
        >
            <img
                src="/photos/F for får.png"
                aria-hidden="true"
                class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none blur-sm scale-105"
            />
            <video
                src="/videos/placeholder.mp4"
                autoplay
                loop
                muted
                playsinline
                preload="none"
                class="absolute top-0 left-0 w-full h-full object-cover pointer-events-none select-none"
                style="min-width: 100%; min-height: 320px"
            ></video>
        </div>
        <div class="relative z-10 flex items-center justify-center h-full">
            <h1
                class="mt-64 text-white text-center text-3xl sm:text-5xl z-10 bg-black/30 px-2 py-2 backdrop-blur-md rounded-xl"
            >
            </h1>
        </div>
    </div>

    <div class="w-300 mx-auto">
        <TheVelkomst />
        <TheFotokarrusel />
        <ThePriser />
        <TheReglerForSpillet />
        <TheArrangementer />
        <TheKontakt />
    </div>
</div>


</template>
