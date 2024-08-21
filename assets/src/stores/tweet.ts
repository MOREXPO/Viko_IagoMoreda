import { defineStore } from 'pinia';
import axios from 'axios';
const apiHost = import.meta.env.VITE_HOST;
export const tweetStore = defineStore('tweet', {
  state: () => ({
    hydra_member: [],
    hydra_totalItems: 0,
    loading: false,
    loaded: false,
  }),
  actions: {
    async fetchTweets() {
      this.loading = true;
      try {
        const response = await axios.get(`http://${apiHost}/api/tweets`);
        this.hydra_member = response.data['hydra:member'];
        this.hydra_totalItems = response.data['hydra:totalItems'];
        this.loaded = true;
      } catch (error) {
        console.error("Error fetching tweets:", error);
      } finally {
        this.loading = false;
      }
    },
  },
});
