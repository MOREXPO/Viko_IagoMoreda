<template>
    <div id="timeline-chart" style="width: 100%; height: 400px;"></div>
  </template>
  
  <script>
  import { mapState } from 'pinia';
  import { tweetStore } from '../stores/tweet';
  
  export default {
    name: 'TimelineChart',
    computed: {
      ...mapState(tweetStore, {
        tweets: store => store.hydra_member,
      }),
      tweetsPerDay() {
        const tweetCounts = {};
        this.tweets.forEach(tweet => {
          const date = new Date(tweet.published_at).toISOString().split('T')[0];
          tweetCounts[date] = (tweetCounts[date] || 0) + 1;
        });
        return Object.keys(tweetCounts).map(date => [new Date(date), tweetCounts[date]]);
      }
    },
    mounted() {
      this.loadGoogleCharts();
    },
    methods: {
      loadGoogleCharts() {
        const script = document.createElement('script');
        script.src = 'https://www.gstatic.com/charts/loader.js';
        script.onload = () => {
          google.charts.load('current', { packages: ['corechart', 'timeline'] });
          google.charts.setOnLoadCallback(this.drawTimelineChart);
        };
        document.head.appendChild(script);
      },
      drawTimelineChart() {
        const data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Tweets');
        data.addRows(this.tweetsPerDay);
  
        const options = {
          title: 'Tweets a lo largo del tiempo',
          hAxis: {
            format: 'MMM dd, yyyy',
            title: 'Tiempo'
          },
          vAxis: {
            title: 'Numero de Tweets'
          }
        };
  
        const chart = new google.visualization.LineChart(this.$el);
        chart.draw(data, options);
      }
    }
  };
  </script>
  