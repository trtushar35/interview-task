<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    canResetPassword: Boolean,
    errorMessage: String,
    successMessage: String,
    warningMessage: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('backend.login.post'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {

        },
    });
};
</script>

<template>

    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <!-- Sign in heading -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">
                Sign in
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Login to your designated account from here
            </p>
        </div>

        <!-- Alert Messages -->
        <div v-if="errorMessage" class="mb-4 rounded-md bg-red-50 p-3">
            <p class="text-sm text-red-600">{{ errorMessage }}</p>
        </div>
        <div v-if="successMessage" class="mb-4 rounded-md bg-green-50 p-3">
            <p class="text-sm text-green-600">{{ successMessage }}</p>
        </div>
        <div v-if="warningMessage" class="mb-4 rounded-md bg-yellow-50 p-3">
            <p class="text-sm text-yellow-600">{{ warningMessage }}</p>
        </div>

        <!-- Form -->
        <form class="space-y-5" @submit.prevent="submit">
            <!-- Email field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail
                </label>
                <input id="email" v-model="form.email" name="email" type="email" autocomplete="email" required
                    placeholder="example@gmail.com"
                    class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-sm" />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <!-- Password field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <div class="relative">
                    <input id="password" v-model="form.password" name="password"
                        :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required
                        placeholder="example@gmail.com"
                        class="appearance-none block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-sm" />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <svg v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                    {{ form.errors.password }}
                </p>
            </div>

            <!-- Remember me and Forgot password -->
            <div class="flex items-center justify-between pt-1">
                <div class="flex items-center">
                    <input id="remember-me" v-model="form.remember" name="remember-me" type="checkbox"
                        class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded cursor-pointer" />
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <Link :href="'#'" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Forgot Password?
                    </Link>
                </div>
            </div>

            <!-- Submit button -->
            <div class="pt-2">
                <button type="submit" :disabled="form.processing"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md">
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign in</span>
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>

<style scoped>
/* Custom focus styles for inputs */
input:focus {
    box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
}

input::placeholder {
    color: #9CA3AF;
}

/* Checkbox custom styling */
input[type="checkbox"]:checked {
    background-color: #f97316;
    border-color: #f97316;
}

input[type="checkbox"]:focus {
    ring-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}
</style>