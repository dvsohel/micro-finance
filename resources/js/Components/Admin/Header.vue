<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

defineEmits(['toggle-sidebar']);
const page = usePage();
const menuOpen = ref(false);
const user = computed(() => page.props.auth?.user ?? {});
const initials = computed(() => (user.value.name || 'Admin').split(' ').filter(Boolean).slice(0, 2).map((part) => part[0]).join('').toUpperCase());
const logoutForm = useForm({});
const logout = () => logoutForm.post(route('logout'));
</script>

<template>
    <header class="admin-header">
        <div class="d-flex align-items-center gap-3 flex-grow-1">
            <button class="admin-icon-btn border-0" type="button" aria-label="Toggle navigation" @click="$emit('toggle-sidebar')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="input-group header-search">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input class="form-control" type="search" placeholder="Search anything..." aria-label="Search" />
                <span class="input-group-text d-none d-sm-flex small">⌘ K</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 gap-sm-3">
            <button class="admin-icon-btn" type="button" aria-label="Notifications" title="No new notifications">
                <i class="bi bi-bell"></i>
                <span class="notification-dot"></span>
            </button>
            <div class="dropdown" :class="{ 'show': menuOpen }">
                <button class="btn d-flex align-items-center gap-2 p-0 border-0" type="button" aria-label="User menu" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen">
                    <span class="admin-avatar">{{ initials }}</span>
                    <span class="admin-profile-copy text-start d-none d-sm-block">
                        <span class="admin-profile-name d-block">{{ user.name || 'Administrator' }}</span>
                        <span class="admin-profile-role">Admin account</span>
                    </span>
                    <i class="bi bi-chevron-down small text-secondary d-none d-sm-inline"></i>
                </button>
                <div v-if="menuOpen" class="dropdown-menu dropdown-menu-end show mt-2 border-0 shadow-sm p-2">
                    <div class="dropdown-item-text px-2 pb-2 small text-secondary">Signed in as <strong class="d-block text-dark">{{ user.email }}</strong></div>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item rounded-2 text-danger" type="button" :disabled="logoutForm.processing" @click="logout">
                        <i class="bi bi-box-arrow-right me-2"></i>{{ logoutForm.processing ? 'Signing out…' : 'Log out' }}
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>
