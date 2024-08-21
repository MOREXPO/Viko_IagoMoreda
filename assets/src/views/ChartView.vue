<template>
  <v-container>
    <v-card>
      <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
        <v-tab :value="1">Nube de etiquetas</v-tab>
        <v-tab :value="2">Gráfico temporal</v-tab>
        <v-tab :value="3">Gráfico de sentimientos</v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item :value="1">
          <TagCloudChart v-if="tab === 1" />
        </v-tab-item>
        <v-tab-item :value="2">
          <TimelineChart v-if="tab === 2" />
        </v-tab-item>
        <v-tab-item :value="3">
          <SentimentChart v-if="tab === 3" />
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import TagCloudChart from '../components/TagCloudChart.vue';
import TimelineChart from '../components/TimelineChart.vue';
import SentimentChart from '../components/SentimentChart.vue';

export default {
  name: 'SimpleChart',
  components: {
    TagCloudChart,
    TimelineChart,
    SentimentChart,
  },
  data() {
    return {
      tab: 1,
    };
  },
};
</script>
