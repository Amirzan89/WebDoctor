<template>
    <div>
        
    </div>
</template>
<script setup lang="ts">
import { onMounted, reactive, ref, watch } from 'vue';
import { useFetchDataStore } from '~/store/FetchData';
const publicConfig = useRuntimeConfig().public;
definePageMeta({
    name: 'dashboard',
    layout: 'authenticated',
});
useHead({
    title:`Dashboard | ${publicConfig.appName}`
});
const local = reactive({
    isDoneFetch: false,
    fetchedUserAuth: null,
    fetchedViewData: null,
});
useAsyncData(async () => {
    const res = await useFetchDataStore().fetchData();
    console.log('hsilll ress', res)
    if(res ==  undefined || res.status == 'error'){
        console.log('mobhh la', res);
        return;
    }else{
        console.log('ws mariiiii', local.isDoneFetch)
        local.isDoneFetch = true;
        console.log('ws kenkekkk', local.isDoneFetch)
        local.fetchedViewData = res.data.other;
    }
});
</script>