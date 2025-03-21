<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 31" :class="className" v-bind="$attrs">
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M 8.894531 17.347656 L 5.046875 10.132812 L 17.957031 2.953125 L 29.078125 6.125 Z M 8.894531 17.347656"
        />
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M 0.910156 24.632812 L 20.210938 13.902344 L 23.933594 21.1875 L 12.03125 27.808594 Z M 0.910156 24.632812"
        />
    </svg>
</template>
