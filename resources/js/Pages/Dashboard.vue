<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <button @click="()=>showRandomUser($page.props.auth.user.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Show Random User
            </button>
            <div v-if="randomUser" class="mt-4">
                <h2 class="text-lg font-semibold mb-2">Random User</h2>
                <div class="border p-4 rounded">
                    <div class="flex items-center mb-4">
                        <img :src="'storage/users-avatar/' +randomUser.avatar" alt="User Avatar" class="h-10 w-10 rounded-full mr-4">
                        <div>
                            <p class="text-gray-800 font-semibold">{{ randomUser.name }}</p>
                            <p class="text-gray-500">{{ randomUser.gender }}</p>
                        </div>
                    </div>
                    <div>
                        <button @click="()=>acceptUser(randomUser.id)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Accept
                        </button>
                        <button @click="()=>showRandomUser($page.props.auth.user.id)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Next
                        </button>
                    </div>
                </div>
            </div>
            <div v-else-if="onlineUsers.length === 1" class="mt-4">
                <h2 class="text-lg font-semibold mb-2">Random User</h2>
                <div class="border p-4 rounded">
                    No users are currently active
                </div>
            </div>
        </div>
    </div>
</div>


     </AuthenticatedLayout>
</template>

<script>

export default {

    data() {
        return {
            onlineUsers: [],
            randomUser: null,
        };
    },
    mounted() {
        window.Echo.join(`online`)
            .here(users => {
                this.onlineUsers = users;
            })
            .joining(user => {
                this.onlineUsers.push(user);
            })
            .leaving(user => {
                this.onlineUsers = this.onlineUsers.filter(u => u.id !== user.id);
            })
            .error(error => {
                console.error(error);
            });
    },
    methods: {
showRandomUser(currentUser) {
            if (this.onlineUsers.length > 0) {
        const filteredUsers = this.onlineUsers.filter(user => user.id !== currentUser);
        if (filteredUsers.length > 0) {
            const randomIndex = Math.floor(Math.random() * filteredUsers.length);
            this.randomUser = filteredUsers[randomIndex];
        }
            }
        },


        acceptUser(acceptedUser) {
    const route = `/chat?acceptedUser=${acceptedUser}`;
    window.location.href = route;

}
    }
};
</script>
