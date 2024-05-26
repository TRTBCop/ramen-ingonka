<template>
  <div id="chart">
    <apexchart type="line" height="350" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>

<script setup lang="ts">
  import type { ApexOptions } from 'apexcharts';
  import { PropType } from 'vue';

  const props = defineProps({
    scoreList: {
      type: Array as PropType<(null | number)[]>,
      default: null,
    },
    totalAnswersList: {
      type: Array as PropType<(null | number)[]>,
      default: null,
    },
  });

  const series = [
    {
      name: '성취도',
      type: 'bar',
      data: props.scoreList,
    },
    {
      name: '문제수',
      type: 'line',
      data: props.totalAnswersList,
    },
  ];

  const chartOptions: ApexOptions = {
    plotOptions: {
      bar: {
        columnWidth: '50%', // 막대의 너비를 70%로 설정
      },
    },
    chart: {
      height: 350,
      animations: {
        enabled: false,
      },
      toolbar: {
        show: false,
      },
      zoom: {
        enabled: false,
      },
    },
    stroke: {
      width: [0, 2],
      dashArray: 9,
    },
    colors: ['#54B2FF', '#5C6373'],
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        return opts.seriesIndex === 0 ? val + '%' : String(val);
      },
      offsetY: -10,
      background: {
        borderWidth: 0,
      },
    },
    markers: {
      size: 3,
    },
    labels: ['3주 전', '2주 전', '1주 전', '이번 주'],
    yaxis: [
      {
        min: 0,
        max: 100,
        tickAmount: 4,
      },
      {
        opposite: true,
      },
    ],
  };
</script>
