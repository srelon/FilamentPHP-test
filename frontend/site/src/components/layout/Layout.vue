<template>
    <div class="layout">
        <AppHeader />
        <main class="layout__main">
            <router-view />
        </main>
        <AppFooter />
    </div>
</template>

<script setup lang="ts">
import AppHeader from './AppHeader.vue'
import AppFooter from './AppFooter.vue'
</script>

<style lang="scss" scoped>
.layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;

    &__main {
        flex: 1;
    }
}
</style>
