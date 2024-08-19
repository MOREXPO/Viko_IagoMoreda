import { defineStore } from 'pinia'
import axios from 'axios';

export const personaStore = defineStore({
  id: "persona",
  state: () => ({
    personas: {},
    store: [],
    lastPage: {},
    loading: true,
  }),
  getters: {
    getPersonas: (state) => {
      return state.personas
    },
    getStore: (state) => {
      return state.store
    },
    getLastPage: (state) => {
      return state.lastPage
    },
    getLoading: (state) => {
      return state.loading
    },
  },
  actions: {
    async getApiPersonas(page, search = '', sortBy = 'id', orderBy = 'desc') {
      if (!(search in this.personas)) {
        this.personas[search] = {};
      }
      if (!(sortBy in this.personas[search])) {
        this.personas[search][sortBy] = {};
      }
      if (!(orderBy in this.personas[search][sortBy])) {
        this.personas[search][sortBy][orderBy] = {};
      }
      if (!(page in this.personas[search][sortBy][orderBy])) {
        this.loading = true;
        try {
          let response = null;
          response = await axios.get(`http://localhost/api/personas?nombre=${search}&page=${page}&order[${sortBy}]=${orderBy}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          this.$patch(state => {
            state.personas[search][sortBy][orderBy][page] = response.data['hydra:member'];
          });
          if (!(search in this.lastPage)) {
            this.lastPage[search] = {};
          }
          if (!(sortBy in this.lastPage[search])) {
            this.lastPage[search][sortBy] = {};
          }
          if (!(orderBy in this.lastPage[search][sortBy])) {
            this.lastPage[search][sortBy][orderBy] = {};
          }
          if (!(page in this.lastPage[search][sortBy][orderBy])) {
            if (response.data['hydra:view'] && response.data['hydra:view']['hydra:last']) {
              const match = response.data['hydra:view']['hydra:last'].match(/page=(\d+)/);
              if (match) {
                this.lastPage[search][sortBy][orderBy] = parseInt(match[1], 10);
              }
            } else {
              this.lastPage[search][sortBy][orderBy] = 1;
            }
          }
          console.log(this.personas);
        } catch (error) {
          console.error('Error:', error);
        } finally {
          this.loading = false;
        }
      }
    },
    async getApiPersona(endpoint) {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost' + endpoint);
        this.$patch(state => {
          state.store = this.updateGroup(response.data);
        });
      } catch (error) {
        console.error(error);
      } finally {
        this.loading = false;
      }

    },
    updateGroup(entity) {
      let newList = [...this.store.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => a.id - b.id);
      return newList;
    }
  }
})
