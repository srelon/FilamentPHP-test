<template>
    <Layout>
        <HomeHero />
        <CategoryStrip />
        <BestsellersSection />
        <BestAuthorSection />
        <BestRatedSection />
        <BlogSection />
    </Layout>
</template>

<script setup lang="ts">
import Layout from '@/components/layout/Layout.vue'
import HomeHero from '@/components/ui/shop/HomeHero.vue'
import CategoryStrip from '@/components/ui/shop/CategoryStrip.vue'
import BestsellersSection from '@/components/ui/shop/BestsellersSection.vue'
import BestAuthorSection from '@/components/ui/shop/BestAuthorSection.vue'
import BestRatedSection from '@/components/ui/shop/BestRatedSection.vue'
import BlogSection from '@/components/ui/shop/BlogSection.vue'
</script>
