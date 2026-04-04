<template>
    <div class="overflow-x-auto rounded-lg border border-earth-200">
        <table class="min-w-full divide-y divide-earth-200">
            <thead class="bg-farm-50">
                <tr>
                    <slot name="header"></slot>
                </tr>
            </thead>
            <tbody class="divide-y divide-earth-100 bg-white">
                <slot></slot>
            </tbody>
        </table>
    </div>
</template>
