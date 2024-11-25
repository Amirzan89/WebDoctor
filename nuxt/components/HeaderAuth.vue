<template>
    <div class="layout-topbar">
        <NuxtLink to="/" class="layout-topbar-logo" style="width: max-content">
            <img src="~/assets/images/logo.png" alt="logo" />
            <span>{{ publicConfig.appName }}</span>
        </NuxtLink>
        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
            <i class="pi pi-bars"></i>
        </button>
        <div class="tw-absolute tw-right-5 tw-flex tw-flex-row tw-gap-5 tw-justify-around">
            <NuxtLink to="/profile" class="tw-flex tw-items-center tw-justify-center tw-gap-3 tw-text-palText">
                <span class="tw-text-lg tw-font-medium">{{ fetchDataS.cacheAuth?.nama_lengkap ?? 'User' }}</span>
                <div class="tw-relative tw-right-0 tw-w-9 tw-h-9 tw-rounded-full tw-pointer-events-none">
                    <Skeleton v-if="isLoadingImg" shape="rectangle" width="100%" height="100%" borderRadius="20px"/>
                    <img v-if="fetchDataS.cacheAuth == null" src="~/assets/images/logo.png" class="tw-absolute tw-left-1/2 -tw-translate-x-1/2 tw-top-1/2 -tw-translate-y-1/2 tw-w-full tw-h-full tw-object-cover"/>
                    <img ref="imageRefs" v-if="fetchDataS.cacheAuth.foto != undefined && fetchDataS.cacheAuth.foto != ''" :src="fetchDataS.cacheAuth?.foto" alt="" class="tw-absolute tw-left-1/2 -tw-translate-x-1/2 tw-top-1/2 -tw-translate-y-1/2 tw-w-full tw-h-full tw-object-cover" style="clip-path: circle();" @load="isLoadingImg = false" @error="isLoadingImg = false"/>
                </div>
            </NuxtLink>
        </div>
    </div>
</template>
<style scoped>
    a{
        transition: color 0.2s ease-in;
    }
</style>
<script setup lang="ts">
import { useFetchDataStore } from '~/store/FetchData';
const publicConfig = useRuntimeConfig().public;
const fetchDataS = useFetchDataStore();
const route = useRoute();
const emit = defineEmits(['menu-toggled']);
const isLoadingImg: Ref = ref(false);
const imageRefs: Ref = ref(null);
const resetImage = async(refImg: any, callback: any) => {
    await callback();
    if(!refImg) return;
    const foto = fetchDataS.cacheAuth.foto;
    fetchDataS.cacheAuth.foto = '';
    nextTick(() => {
        fetchDataS.cacheAuth.foto = foto;
    });
};
watch(() => route.fullPath, async() => {
    if(fetchDataS.processFetch.isDone == 'loading' || fetchDataS.processFetch.isDone == 'error') return;
    // isLoadingImg.value = true;
    const res = await fetchDataS.fetchAuth();
    if(res ==  undefined || res.status == 'error'){
        return;
    }
    // resetImage(imageRefs.value, async() => {
    // });
}, { immediate:true });
const onMenuToggle = () => {
    emit('menu-toggled');
}
</script>