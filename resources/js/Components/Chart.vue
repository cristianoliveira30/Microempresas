<script setup>
import { defineProps } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

defineProps({
  type: { type: String, default: 'bar' },
  options: Object,
  series: Array,
  height: { type: [String, Number], default: 300 }
});
</script>

<template>
  <VueApexCharts :type="type" :options="options" :series="series" :height="height" />
</template>
