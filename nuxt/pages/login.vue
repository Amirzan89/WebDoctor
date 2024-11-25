<template>
    <template v-if="useNotFoundStore().isNotFound || fetchDataS.processFetch.isDone  == 'error'"><NotFoundComponent/></template>
    <div v-else :style="{backgroundImage: `url(${bgLogin})`}" class="tw-w-full tw-h-screen">
        <div class="tw-relative tw-left-1/2 tw-top-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-w-3/4 3xs:tw-h-1/4 xs:tw-h-1/3 md:tw-h-1/2 xl:tw-h-2/3 3xs:tw-rounded-lg xl:tw-rounded-xl tw-outline tw-outline-palBg tw-outline-[12px] -tw-outline-offset-2 tw-flex tw-justify-between">
            <div class="tw-bg-palBg tw-flex-1 tw-relative">
                <div class="tw-w-5/6 tw-absolute tw-left-1/2 -tw-translate-x-1/2 tw-top-1/5 -tw-translate-y-1/2">
                    <h3 class="3xs:tw-text-sm xs:tw-text-lg sm:tw-text-2xl md:tw-text-3xl lg:tw-text-4xl xl:tw-text-5xl tw-font-semibold tw-m-0">Selamat Datang di Cermat Bertani</h3>
                    <p class="3xs:tw-text-xs xs:tw-text-base sm:tw-text-base md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl tw-font-light tw-m-0 3xs:tw-mt-0.5 sm:tw-mt-1 md:tw-mt-3 xl:tw-mt-5">Selamat datang Admin, silahkan login dengan akun anda</p>
                </div>
                <form class="tw-relative tw-w-5/6 tw-min-h-min tw-left-1/2 -tw-translate-x-1/2 tw-top-1/2 -tw-translate-y-1/2" @submit.prevent="loginForm">
                    <FloatLabel class="3xs:tw-mt-15 xs:tw-mt-12 sm:tw-mt-17 lg:tw-mt-18">
                        <label for="inpEm" class="tw-left-1.5 sm:tw-left-3 xl:tw-left-4 3xs:tw-text-sm xs:tw-text-base sm:tw-text-lg md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl tw-text-palText">Email</label>
                        <InputText type="text" ref="inpEmail" class="tw-w-full md:tw-border-1 xl:tw-border-2 tw-border-palText hover:tw-border-palText focus:tw-border-palText focus:tw-outline-palText 3xs:tw-pl-[7px] xl:tw-pl-3 3xs:tw-text-xs xs:tw-text-sm sm:tw-text-base md:tw-text-lg lg:tw-text-xl xl:tw-text-2xl 3xs:tw-rounded-md xl:tw-rounded-lg 3xs:tw-h-7 xs:tw-h-8 md:tw-h-9 lg:tw-h-10 xl:tw-h-13 3xs:tw-outline-0 3xs:focus:tw-outline-1 xl:focus:tw-outline-3" v-model="input.email" @input="inpChange('email')"/>
                    </FloatLabel>
                    <FloatLabel class="tw-mt-6 3xs:tw-mt-7 xs:tw-mt-8 sm:tw-mt-9 lg:tw-mt-10 xl:tw-mt-12 2xl:tw-mt-15">
                        <label for="inpPass" class="tw-left-1.5 sm:tw-left-3 xl:tw-left-4 3xs:tw-text-sm xs:tw-text-base sm:tw-text-lg md:tw-text-xl lg:tw-text-2xl xl:tw-text-3xl tw-text-palText">Password</label>
                        <div class="tw-flex tw-items-center">
                            <InputText type="password" ref="inpPassword" class="tw-w-full md:tw-border-1 xl:tw-border-2 tw-border-palText hover:tw-border-palText focus:tw-border-palText focus:tw-outline-palText 3xs:tw-pl-[7px] xl:tw-pl-3 3xs:tw-text-xs xs:tw-text-sm sm:tw-text-base md:tw-text-lg lg:tw-text-xl xl:tw-text-2xl 3xs:tw-rounded-md xl:tw-rounded-lg 3xs:tw-h-7 xs:tw-h-8 md:tw-h-9 lg:tw-h-10 xl:tw-h-13 3xs:tw-outline-0 3xs:focus:tw-outline-1 xl:focus:tw-outline-3 3xs:tw-pr-8 md:tw-pr-9 lg:tw-pr-11 xl:tw-pr-14" v-model="input.password" @input="inpChange('password')"/>
                            <div class="tw-absolute tw-top-1/2 -tw-translate-y-1/2 3xs:tw-right-1 sm:tw-right-2 xl:tw-right-3" @click="showPass">
                                <div class="tw-relative 3xs:tw-w-[17px] lg:tw-w-8 xl:tw-w-10 tw-flex tw-items-center tw-justify-center">
                                    <I_eye_slash class="tw-absolute tw-fill-palText tw-w-full tw-transition tw-duration-400" :class="input.password === '' || local.isPasswordShow ? 'tw-opacity-0 tw-pointer-events-none' : 'tw-opacity-100 tw-cursor-pointer'"/>
                                    <I_eye class="tw-absolute tw-fill-palText tw-w-full tw-transition tw-duration-[400ms]" :class="input.password === '' || !local.isPasswordShow ? 'tw-opacity-0 tw-pointer-events-none' : 'tw-opacity-100 tw-cursor-pointer'"/>
                                </div>
                            </div>
                        </div>
                    </FloatLabel>
                    <Button label="Primary" type="submit" class="tw-relative 3xs:tw-left-1/3 sm:tw-left-1/5 lg:tw-left-[15%] -tw-translate-x-1/2 3xs:tw-mt-4 xs:tw-mt-5 sm:tw-mt-6 md:tw-mt-8 lg:tw-mt-13 3xs:tw-text-base sm:tw-text-xl md:tw-text-2xl lg:tw-text-3xl 3xs:tw-px-2 sm:tw-px-3 lg:tw-px-4 xl:tw-px-5 2xl:tw-px-6 3xs:tw-py-1 lg:tw-py-2 xl:tw-py-2 3xs:tw-rounded-lg xl:tw-rounded-2xl tw-flex tw-items-center tw-justify-center 3xs:tw-font-medium xl:tw-font-semibold tw-text-palBg">Login</Button>
                </form>
            </div>
            <div class="tw-w-1/3 tw-h-full tw-bg-blue-500">
                <span class="tw-relative tw-left-1/2 -tw-translate-x-1/2 tw-top-1/5 -tw-translate-y-1/2 3xs:tw-w-[85%] xs:-w-full tw-flex tw-justify-center tw-items-center 3xs:tw-gap-1 sm:tw-gap-3 xl:tw-gap-5">
                    <I_location class="3xs:tw-w-[18px] sm:tw-w-5 md:tw-w-6 xl:tw-w-10 tw-fill-palBg"/>
                    <p class="tw-text-palBg tw-text-sm 3xs:tw-text-xs sm:tw-text-base md:tw-text-xl xl:tw-text-2xl 2xl:tw-text-3xl 3xs:tw-font-medium sm:tw-font-[550] xl:tw-font-semibold">DKPP Kota Kediri</p>
                </span>
                <img src="~/assets/images/logo.png" class="3xs:tw-w-18 xs:tw-w-24 phone:tw-w-27 sm:tw-w-40 md:tw-w-48 xl:tw-w-100 tw-relative tw-left-1/2 -tw-translate-x-1/2 tw-top-1/2 -tw-translate-y-1/2"/>
            </div>
        </div>
    </div>
</template>
<style scoped lang="scss">
.fade-out{
    animation: fadeOut 0.75s ease forwards;
}
@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        display: none;
    }
}
</style>
<script setup lang="ts">
import * as z from 'zod';
import { useToast } from 'primevue/usetoast';
import { ref, reactive } from "vue";
import { eventBus } from '~/app/eventBus';
import NotFoundComponent from '~/components/NotFound.vue';
import { useFetchDataStore } from "~/store/FetchData";
import { useNotFoundStore } from '~/store/NotFound';
import { Login } from '~/composables/api/auth';
import bgLogin from '~/assets/images/login.png';
import I_location from '~/assets/icons/location.svg';
import I_eye from '~/assets/icons/eye.svg';
import I_eye_slash from '~/assets/icons/eye-slash.svg';
const publicConfig = useRuntimeConfig().public;
const fetchDataS = useFetchDataStore();
const toast = useToast();
definePageMeta({
    name: 'Login',
    layout: 'default',
});
useHead({
    title:`Login | ${publicConfig.appName}`,
});
const local = reactive({
    isRequestInProgress: false,
    isUpdated: false,
    isPasswordShow: false,
});
const input = reactive({
    email:'',
    password:'',
    checkboxValue: '',
});
const inpEmail: Ref = ref(null);
const inpPassword: Ref = ref(null);
// const errorRef: Ref = ref(null);
const cosValidationObject = {
    email: inpEmail,
    password: inpPassword,
};
const cosValidationArray = Object.keys(cosValidationObject) as Array<keyof typeof cosValidationObject>;
type cosValidationType = typeof cosValidationArray[number];
const getElement = (key: keyof typeof cosValidationObject) => {
    return cosValidationObject[key].value?.$el;
};
const showPass = () => {
    if(local.isPasswordShow){
        getElement('password').type = 'password';
        local.isPasswordShow = false;
    }else{
        getElement('password').type = 'text';
        local.isPasswordShow = true;
    }
};
const formSchema = z.object({
    email: z.string().min(1, 'Email Harus diisi !').email('Masukkan email dengan benar !'),
    password: z.string().min(1, 'Password Harus diisi !')
});
const checkValidator = () => {
    const validSchema = formSchema.safeParse(input);
    if(validSchema.success){
        return { status: 'success' };
    }
    let errMessage = '', divItem = '', dValid = validSchema.error.format();
    // errorRef.value = dValid;
    Object.keys(dValid).filter((key) => (cosValidationArray as readonly string[]).includes(key)).forEach((key: string) => {
        if(errMessage == ''){
            const fieldValue = dValid[key as keyof typeof dValid];
            if (typeof fieldValue === 'object' && fieldValue !== null && '_errors' in fieldValue) {
                const fieldErrors = fieldValue._errors;
                if (fieldErrors && fieldErrors.length > 0) {
                    divItem = key;
                    errMessage = fieldErrors[0];
                }
            }
        }
    });
    return { status: 'error', message: errMessage, item: divItem };
}
const inpChange = (div: cosValidationType, isError = false, isNeedValidator = false): void => {
    if(isNeedValidator){
        const isValidForm = checkValidator();
        if(isValidForm.status == 'error') return inpChange(isValidForm.item as cosValidationType, true);
    }
    const itemE = getElement(div);
    if(!itemE) return console.warn(`Element for ${div} not found.`);
    if(isError){
        itemE.classList.remove('tw-border-palText', 'hover:tw-border-palText', 'focus:tw-border-palText', 'focus:tw-outline-palText');
        itemE.classList.add('tw-border-popup_error', 'hover:tw-border-popup_error', 'focus:tw-border-popup_error', 'focus:tw-outline-popup_error');
    }else{
        itemE.classList.remove('tw-border-popup_error', 'hover:tw-border-popup_error', 'focus:tw-border-popup_error', 'focus:tw-outline-popup_error');
        itemE.classList.add('tw-border-palText', 'hover:tw-border-palText', 'focus:tw-border-palText', 'focus:tw-outline-palText');
    }
};
const loginForm = async() => {
    try{
        if(local.isRequestInProgress) return;
        const isValidForm = checkValidator();
        if(isValidForm.status == 'error'){
            inpChange(isValidForm.item as cosValidationType, true);
            return toast.add({ severity: 'error', summary: 'Gagal Login', detail: isValidForm.message, group: 'br', life: 3000 })
        }
        local.isRequestInProgress = true;
        eventBus.emit('showLoading');
        let login = await Login({email: input.email, password: input.password});
        if(login.status === 'success'){
            local.isRequestInProgress = false;
            eventBus.emit('closeLoading');
            toast.add({ severity: 'success', summary: 'Berhasil Login', detail: login.message, group: 'br', life: 3000 });
            setTimeout(function(){
                navigateTo('/beranda');
            }, 1500);
        }else if(login.status === 'error'){
            local.isRequestInProgress = false;
            eventBus.emit('closeLoading');
            toast.add({ severity: 'error', summary: 'Gagal Login', detail: login.message, group: 'br', life: 3000 });
            cosValidationArray.forEach((field) => {
                if (login.message.toLowerCase().includes(field)) {
                    inpChange(field, true);
                }
            });
        }
    }catch(err){
        eventBus.emit('closeLoading');
        console.error(err);
        // toast.add({ severity: 'error', summary: 'Gagal Login', detail: err, group: 'br', life: 3000 });
    }
};
</script>