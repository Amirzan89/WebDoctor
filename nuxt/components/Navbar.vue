<template>
    <div class="tw-fixed tw-w-50 tw-h-screen tw-select-none tw-transform tw-duration-200" style="box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.02), 0px 0px 2px rgba(0, 0, 0, 0.05), 0px 1px 4px rgba(0, 0, 0, 0.08);">
        <NuxtLink to="/beranda" class="tw-mt-5 tw-relative tw-left-1/2 -tw-translate-x-1/2 tw-w-3/4 tw-flex tw-justify-center tw-items-center">
            <img src="~/assets/images/logo.png" alt="" class="tw-w-15">
            <span class="tw-text-2xl tw-font-semibold tw-text-palText"> {{ publicConfig.appName }}</span>
        </NuxtLink>
        <ul class="tw-relative tw-left-1/2 -tw-translate-x-1/2 tw-flex tw-flex-col tw-gap-5 tw-justify-center tw-items-center tw-p-0">
            <template v-for="(item, index) in routeItems" :key="index">
                <NuxtLink :to="item.link" class="tw-w-5/6 tw-h-12 tw-rounded-2xl hover:tw-bg-pal1 tw-relative tw-flex tw-flex-row tw-cursor-pointer tw-items-center tw-justify-evenly hover:tw-text-palBg" :class="route.path.startsWith(item.link) ? 'tw-text-palBg tw-bg-pal1' : 'tw-text-palText tw-bg-transparent'">
                    <component :is="item.icon" class="tw-w-7"/>
                    <span class="">{{ item.label }}</span>
                </NuxtLink>
            </template>
        </ul>
        <Button class="tw-group tw-absolute tw-bottom-10 tw-left-1/2 -tw-translate-x-1/2 tw-gap-3 tw-bg-transparent hover:tw-bg-pal1 tw-border-palText hover:tw-border-pal1" @click="isLogout = true">
            <I_Logout class="tw-w-7 tw-fill-palText group-hover:tw-fill-palBg"/>
            <span class="tw-text-palText group-hover:tw-text-palBg tw-text-xl tw-font-medium">Keluar</span>
        </Button>
    </div>
    <Dialog v-model:visible="isLogout" header="Keluar ?" :modal="true" :closeOnEscape="true" :dismissableMask="true" class="">
        <div class="tw-flex tw-flex-col">
            <span class="3xs:tw-text-sm xs:tw-text-base sm:tw-text-lg md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl">Apakah anda ingin keluar ?</span>
            <div class="tw-flex tw-justify-between tw-mt-5">
                <Button class="tw-w-max 3xs:tw-rounded-sm 3xs:tw-px-1.5 xs:tw-px-2 lg:tw-px-5 3xs:tw-py-1.5 tw-text-palBg 3xs:tw-text-xs xs:tw-text-sm sm:tw-text-lg md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl" @click="isLogout = false">Batal</Button>
                <Button class="tw-w-max 3xs:tw-rounded-sm 3xs:tw-px-1.5 xs:tw-px-2 lg:tw-px-5 3xs:tw-py-1.5 tw-text-palBg 3xs:tw-text-xs xs:tw-text-sm sm:tw-text-lg md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl" @click="logout()">Keluar</Button>
            </div>
        </div>
    </Dialog>
</template>
<style scoped lang="scss">
body.nav-inactive div{
    transform: translateX(-100%);
    left: 0;
}
</style>
<script setup lang="ts">
import { useToast } from 'primevue/usetoast';
import { reactive } from 'vue';
import { eventBus } from '~/app/eventBus';
import { useFetchDataStore } from '~/store/FetchData';
import { Logout } from '~/composables/api/auth';
import I_Home from '~/assets/icons/navbar/home.svg';
import I_People from '~/assets/icons/navbar/peoples.svg';
import I_GreenHouse from '~/assets/icons/navbar/green_house.svg';
import I_Logout from '~/assets/icons/navbar/logout.svg';
const publicConfig = useRuntimeConfig().public;
const fetchDataS = useFetchDataStore();
const route = useRoute();
const toast = useToast();
const routeItems = reactive([
    {
        'label': 'Dashboard',
        'icon': markRaw(I_Home),
        'link': '/beranda',
    },
    {
        'label': 'kelola Users',
        'icon': markRaw(I_People),
        'link': '/users',
    },
    {
        'label': 'Kelola Rekapan',
        'icon': markRaw(I_GreenHouse),
        'link': '/rekap',
    },
    {
        'label': 'Kelola acara',
        'icon': markRaw(I_GreenHouse),
        'link': '/acara',
    },
    {
        'label': 'Kelola Admin',
        'icon': markRaw(I_People),
        'link': '/admin',
    },
]);
const isLogout: Ref = ref(false);
const logout = async() => {
    eventBus.emit('showLoading');
    let logout = await Logout();
    eventBus.emit('closeLoading');
    if(logout.status === 'success'){
        fetchDataS.resetFetchData();
        fetchDataS.resetCacheAuth();
        toast.add({ severity: 'success', summary: 'Berhasil Logout', detail: logout.message, group: 'br', life: 3000 });
        setTimeout(function(){
            navigateTo('/login');
        }, 1500);
    }else if(logout.status === 'error'){
        toast.add({ severity: 'error', summary: 'Gagal Logout', detail: logout.message, group: 'br', life: 3000 });
    }
}
</script>