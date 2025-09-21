<template>
  <LandingLayout>
    <!-- Hero Section -->
    <HeroSection :countdown="countdown" />

    <!-- Packages Section -->
    <PaketUmrohSection :packages="packages" />

    <!-- Testimonials Section -->
    <TestimoniSection :testimonials="testimonials" />

    <!-- Timeline Section -->
    <TimelineSection />

    <!-- Gallery Section -->
    <GallerySection :gallery="gallery" />

    <!-- Manasik Section -->
    <ManasikSection />
  </LandingLayout>
</template>

<script setup>
import LandingLayout from '@/Layouts/LandingLayout.vue'
import HeroSection from '@/Components/Landing/HeroSection.vue'
import PaketUmrohSection from '@/Components/Landing/PaketUmrohSection.vue'
import TestimoniSection from '@/Components/Landing/TestimoniSection.vue'
import TimelineSection from '@/Components/Landing/TimelineSection.vue'
import GallerySection from '@/Components/Landing/GallerySection.vue'
import ManasikSection from '@/Components/Landing/ManasikSection.vue'

defineProps({
  packages: Array,
  testimonials: Array,
  gallery: Array,
  countdown: Object
})
</script>