<script setup>
import { ref } from 'vue';
import Sidebar from '../Components/Admin/Sidebar.vue';
import Header from '../Components/Admin/Header.vue';

const sidebarOpen = ref(false);
</script>

<template>
    <div class="admin-wrapper">
        <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" />
        <div v-if="sidebarOpen" class="sidebar-backdrop d-lg-none" @click="sidebarOpen = false"></div>
        <div class="admin-main">
            <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" />
            <main class="admin-content">
                <slot />
            </main>
        </div>
    </div>
</template>
