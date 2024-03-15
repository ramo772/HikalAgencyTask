<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

</script>

<template>
    <Head title="Welcome" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen  bg-center bg-gray-100   selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-[#285BA9] hover:text-gray-900  dark:hover:text-[#F16933] focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="font-semibold  text-[#285BA9] hover:text-gray-900  dark:hover:text-[#F16933] focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</Link>

                <Link v-if="canRegister" :href="route('register')" class="text-[#285BA9] ml-4 font-semibold  hover:text-gray-900  dark:hover:text-[#F16933] focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</Link>
            </template>
        </div>

        <div class=" mx-auto p-6 lg:p-8 flex flex-col items-center">
           <img src="https://www.hikalagency.com/assets/images/logo/fullLogo.png" width="200px" alt="">
<div class=" mx-auto p-6 lg:p-8 flex flex-col items-center">
                <h1 class="text-3xl font-bold text-[#285BA9]">Chat Application</h1>

</div>
<p class="text-lg text-center text-[#F16933] font-bold">Welcome to our chat application, where connection meets convenience! Seamlessly random people from anywhere in the world. With intuitive features and a user-friendly interface, our platform ensures smooth conversations and easy sharing of ideas, moments, and laughter. Join our vibrant community today and experience the joy of staying connected!
</p>
        </div>
    </div>
</template>

