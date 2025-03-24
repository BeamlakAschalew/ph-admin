<template>
    <div>
        <h1>Welcome, {{ page.props.auth.user.first_name }}</h1>
        <p>Phone: {{ page.props.auth.user.phone }}</p>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();

console.log(page.props);
</script>
