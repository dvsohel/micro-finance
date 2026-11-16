<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ canResetPassword: Boolean, status: String });

const showPassword = ref(false);
const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post(route('login'), { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Admin sign in" />
    <main class="login-page d-flex align-items-center">
        <div class="login-shell">
            <section class="login-visual" aria-label="Microfinance workspace illustration">
                <div class="login-brand"><span class="admin-brand-mark"><i class="bi bi-layers-half"></i></span>Micro Finance</div>
                <div class="login-hero">
                    <div class="page-eyebrow text-success-emphasis mb-3">Your business, in focus</div>
                    <h1>Good work starts with a clear view.</h1>
                    <p>A calm, secure space to understand your business and keep your team moving forward.</p>
                </div>
                <div class="login-illustration" aria-hidden="true">
                    <div class="illustration-card"><div class="illustration-line"></div><div class="illustration-line" style="width: 40%; opacity: .55"></div><div class="illustration-bars"><i></i><i></i><i></i><i></i></div></div>
                    <div class="illustration-card"><div class="illustration-line" style="width: 55%"></div><div class="illustration-line" style="width: 76%; opacity: .55"></div><div class="illustration-line" style="width: 66%; opacity: .35"></div></div>
                </div>
                <div class="login-foot">Secure administration · Micro Finance workspace</div>
            </section>

            <section class="login-form-panel">
                <div class="login-form-inner">
                    <div class="d-lg-none login-brand text-dark mb-4"><span class="admin-brand-mark"><i class="bi bi-layers-half"></i></span>Micro Finance</div>
                    <h2>Welcome back</h2>
                    <p class="login-intro">Sign in with your administrator account to continue.</p>

                    <div v-if="status" class="alert alert-success py-2 small" role="status">{{ status }}</div>
                    <form @submit.prevent="submit">
                        <div class="mb-3">
                            <label for="email" class="form-label login-label">Email address</label>
                            <div class="input-group login-input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" v-model="form.email" class="form-control login-input" :class="{ 'is-invalid': form.errors.email }" type="email" autocomplete="username" placeholder="you@company.com" required autofocus />
                            </div>
                            <div v-if="form.errors.email" class="invalid-feedback d-block mt-1">{{ form.errors.email }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2"><label for="password" class="form-label login-label mb-0">Password</label>
                                <Link v-if="canResetPassword" :href="route('password.request')" class="login-link">Forgot password?</Link>
                            </div>
                            <div class="input-group login-input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" v-model="form.password" class="form-control login-input" :class="{ 'is-invalid': form.errors.password }" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" placeholder="Enter your password" required />
                                <button class="input-group-text" type="button" :aria-label="showPassword ? 'Hide password' : 'Show password'" @click="showPassword = !showPassword"><i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i></button>
                            </div>
                            <div v-if="form.errors.password" class="invalid-feedback d-block mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 mb-4">
                            <label class="d-inline-flex align-items-center gap-2 small text-secondary" for="remember"><input id="remember" v-model="form.remember" class="form-check-input login-check mt-0" type="checkbox" /> Remember me</label>
                            <span class="small text-secondary"><i class="bi bi-shield-lock me-1"></i>Secure sign in</span>
                        </div>

                        <button class="btn btn-success login-submit w-100" type="submit" :disabled="form.processing">
                            <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                            {{ form.processing ? 'Signing in…' : 'Sign in to dashboard' }}
                            <i v-if="!form.processing" class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                    <p class="text-center text-secondary small mt-4 mb-0">Need access? Contact your workspace administrator.</p>
                </div>
            </section>
        </div>
    </main>
</template>
