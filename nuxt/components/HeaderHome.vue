<template>
    <header class="tw-sticky tw-top-0 tw-pointer-events-auto tw-flex tw-items-center tw-justify-center tw-bg-white dark:tw-bg-dark_bg">
        <div class="tw-flex tw-flex-row 3xsphone:tw-w-11/12 tw-justify-between tw-items-center">
            <h1 v-if="route.path == '/'" class="3xsphone:tw-text-sm sm:tw-text-xl md:tw-text-2xl xl:tw-text-4xl 2xl:tw-text-5xl tw-text-6xl tw-text-primary dark:tw-text-primary_dark_text tw-font-bold">{{ publicConfig.appName }}</h1>
            <NuxtLink v-else to="/" class="3xsphone:tw-text-sm sm:tw-text-xl md:tw-text-2xl xl:tw-text-4xl 2xl:tw-text-5xl tw-text-6xl tw-text-primary dark:tw-text-primary_dark_text tw-font-bold">{{ publicConfig.appName }}</NuxtLink>
            <div class="tw-flex tw-flex-row 3xsphone:tw-gap-2 sm:tw-gap-5 md:tw-gap-10">
                <ul class="tw-flex tw-flex-column tw-text-l_pa_1 tw-ml-13 tw-items-center 3xsphone:tw-gap-2 sm:tw-gap-4 md:tw-gap-5 lg:tw-gap-6 xl:tw-gap-10">
                    <template v-if="route.path == '/'">
                        <template v-for="(item, index) in routeItems" :key="index">
                            <NuxtLink :href="item.link" class="tw-relative 3xsphone:tw-text-2xs sm:tw-text-sm md:tw-text-base lg:tw-text-xl xl:tw-text-2xl 2xl:tw-text-2xl tw-text-3xl 3xsphone:tw-font-normal xl:tw-font-semibold" :class="route.hash == item.link ? 'tw-text-pal1' : 'tw-text-palText'">
                                <span> {{ item.label  }}</span>
                            </NuxtLink>
                        </template>
                    </template>
                    <template v-else>
                    </template>
                </ul>
                <Button class="tw-w-20 tw-flex tw-justify-center tw-items-center">
                    <NuxtLink to="/login" class="tw-text-palText tw-text-2xl tw-font-medium">Login</NuxtLink>
                </Button>
            </div>
        </div>
    </header>
</template>
<style scoped>
    header{
        z-index: 999;
        height: var(--paddTop);
        scroll-padding-top: var(--paddTop);
    }
    a{
        transition: color 0.2s ease-in;
    }
</style>
<script setup lang="ts">
import { reactive } from 'vue';
const route = useRoute();
const { $gsap } = useNuxtApp();
const gsap = $gsap as GSAP;
const publicConfig = useRuntimeConfig().public;
const routeItems = reactive([
    {
        'label': 'Beranda',
        'link': '#me',
    },
    {
        'label': 'Tentang Kami',
        'link': '#about',
    },
    {
        'label': 'Kategori',
        'link': '#category',
    },
    {
        'label': 'Dokumentasi',
        'link': '#dokumentasi',
    },
]);
let header: any = null;
onMounted(() => {
    header = gsap.utils.selector('header');
});
</script>