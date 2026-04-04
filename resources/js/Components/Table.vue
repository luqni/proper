<template>
    <div class="overflow-x-auto rounded-lg border border-earth-200">
        <table class="min-w-full divide-y divide-earth-200">
            <thead class="bg-gray-50">
                <slot name="head"></slot>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <slot name="body"></slot>
            </tbody>
        </table>
    </div>
</template>
