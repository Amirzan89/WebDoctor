<template>
    <NuxtLayout>
        <slot/>
    </NuxtLayout>
</template>
<style>
    :root{
        --darkMode: none;
        --paddTop: 0px;
    }
    header, main, footer, button{
        transition: var(--darkMode);
    }
</style>
<script setup>
import resolveConfig from 'tailwindcss/resolveConfig';
import tailwindConfig from '~/tailwind.config';
const fullConfig = resolveConfig(tailwindConfig);
let screenCon = null;
const dynamicPad = {
    'phone':'30px',
    'sm':'40px',
    'md':'50px',
    'lg':'60px',
    'xl':'70px',
    '2xl':'70px',
}
const updatePadding = () => {
    const currentWidth = window.innerWidth;
    const breakpoints = [...screenCon].sort((a, b) => b - a);
    const breakpoint = breakpoints.find((fixWidth) => {
        return currentWidth >= fixWidth;
    });
    if(breakpoint !== undefined){
        document.documentElement.style.setProperty('--paddTop', `${dynamicPad[Object.keys(dynamicPad)[screenCon.indexOf(breakpoint)]]}`);
    }else{
        document.documentElement.style.setProperty('--paddTop', '50');
    }
}
onBeforeMount(() => {
    screenCon = Object.values(fullConfig.theme.screens).map((item) => {
        return parseInt(item.replace('px', ''));
    });
    updatePadding();
});
window.onresize = () => {
    updatePadding();
}
</script>