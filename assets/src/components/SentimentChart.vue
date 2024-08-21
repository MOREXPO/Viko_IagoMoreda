<template>
    <div id="sentiment-chart" style="width: 100%; height: 400px;"></div>
  </template>
  
  <script>
  import { mapState } from 'pinia';
  import { tweetStore } from '../stores/tweet';
  
  export default {
    name: 'SentimentChart',
    computed: {
      ...mapState(tweetStore, {
        tweets: store => store.hydra_member,
      }),
      sentimentData() {
        const data = [['Tweet', 'Positive', 'Neutral', 'Negative']];
        this.tweets.forEach((tweet, index) => {
          const { pos, neu, neg } = tweet.sentiment;
          data.push([`Tweet ${index + 1}`, pos, neu, neg]);
        });
        return data;
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
          google.charts.load('current', { packages: ['corechart'] });
          google.charts.setOnLoadCallback(this.drawSentimentChart);
        };
        document.head.appendChild(script);
      },
      drawSentimentChart() {
        const data = google.visualization.arrayToDataTable(this.sentimentData);
  
        const options = {
          title: 'Distribución del sentimiento de los tweets',
          isStacked: true,
          hAxis: {
            title: 'Tweets',
            minValue: 0
          },
          vAxis: {
            title: 'Puntuación del sentimiento'
          },
          colors: ['#8BC34A', '#FFC107', '#F44336']
        };
  
        const chart = new google.visualization.BarChart(this.$el);
        chart.draw(data, options);
      }
    }
  };
  </script>
  