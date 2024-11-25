<template>
    <div v-if="showPreloader" class="tw-fixed tw-left-0 tw-top-0 tw-w-full tw-h-full" style="z-index: 9999">
        <div class="tw-relative tw-w-full tw-h-full tw-left-0 tw-top-0" style="background-color: rgba(0, 0, 0, 0.25)"></div>
        <div class="tw-absolute tw-inline-block tw-left-1/2 tw-top-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-w-20 tw-h-20">
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.45s;"></div>
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.3s"></div>
            <div class="tw-absolute tw-box-border tw-block tw-w-16 tw-h-16 tw-m-[8px] tw-rounded-full" style="border: 8px solid black; border-color: black transparent transparent transparent; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; animation-delay: -0.15s"></div>
        </div>
    </div>
</template>
<style>
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
import { ref, onMounted, watch } from 'vue';
import { eventBus } from '~/app/eventBus';
import { useFetchDataStore } from "~/store/FetchData";
const fetchDataS = useFetchDataStore();
const showPreloader = ref(false);
onMounted(() => {
    eventBus.on('showLoading',function(){
        showLoading();
    });
    eventBus.on('closeLoading',function(){
        closeLoading();
    });
});
watch(() => fetchDataS.processFetch.isDone, () => {
    showPreloader.value = fetchDataS.processFetch.isDone == 'loading';
}, { immediate:true });
const showLoading = () => {
    showPreloader.value = true;
};
const closeLoading = () => {
    showPreloader.value = false;
};
</script>