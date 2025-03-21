<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthLayout
        title="Something doesn't seem right..."
        description="The invitation link you have used doesn't appear to be active anymore. Please request a new one."
    >
        <Head title="Invalid Registration Token" />

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="1">Log in</TextLink>
        </div>
    </AuthLayout>
</template>
