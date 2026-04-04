<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-earth-200">
        <div v-if="$slots.header" class="px-6 py-4 border-b border-earth-100 bg-earth-50">
            <slot name="header"></slot>
        </div>
        <div class="text-gray-900">
            <slot></slot>
        </div>
        <div v-if="$slots.footer" class="px-6 py-4 border-t border-earth-100 bg-earth-50">
            <slot name="footer"></slot>
        </div>
    </div>
</template>
