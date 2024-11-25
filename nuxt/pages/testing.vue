<template>
    <div class="tw-fixed tw-left-0 tw-top-0 tw-w-full tw-h-full babi" style="z-index: 0">
        <div class="tw-relative tw-w-full tw-h-full tw-left-0 tw-top-0" style="background-color: rgba(0, 0, 0, 0.25)"></div>
        <div class="tw-absolute tw-inline-block tw-left-1/2 tw-top-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-w-20 tw-h-20">
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full tw-z-10" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.45s;"></div>
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full tw-z-10" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.3s"></div>
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full tw-z-10" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.15s"></div>
        </div>
    </div>
</template>
<style scoped>
@keyframes lds-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<script setup lang="ts">
definePageMeta({
    name: 'testing',
    layout: 'default',
});
</script>