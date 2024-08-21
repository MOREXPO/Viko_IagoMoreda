<template>
    <v-container>
        <v-card title="Tweets" flat>
            <template v-slot:text>
                <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify" variant="outlined"
                    hide-details single-line></v-text-field>
            </template>

            <v-data-table :headers="headers" :items="filteredTweets" :search="search" :loading="loading" item-key="id">
                <!-- Columna para mostrar imágenes en un carrusel -->
                <template v-slot:item.images="{ item }">
                    <v-carousel v-if="item.images && item.images.length" height="200" cycle hide-delimiters :show-arrows="false">
                        <v-carousel-item v-for="(image, index) in item.images" :key="index">
                            <v-img :src="image" :alt="'Image ' + (index + 1)" contain></v-img>
                        </v-carousel-item>
                    </v-carousel>
                </template>

                <!-- Mostrar el autor del tweet -->
                <template v-slot:item.author="{ item }">
                    <span>{{ item.author }}</span>
                </template>

                <!-- Mostrar el contenido del tweet -->
                <template v-slot:item.content="{ item }">
                    <div>{{ item.content }}</div>
                    <v-chip-group column>
                        <v-chip v-for="(hashtag, index) in item.hashtags" :key="index" class="ma-1" color="blue"
                            text-color="white">
                            {{ hashtag }}
                        </v-chip>
                    </v-chip-group>
                </template>

                <!-- Mostrar la fecha de publicación -->
                <template v-slot:item.published_at="{ item }">
                    {{ new Date(item.published_at).toLocaleString() }}
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { tweetStore } from '../stores/tweet';

export default {
    name: "tableView",
    data() {
        return {
            search: '',
            headers: [
                { key: 'images', title: 'Images' },  // Nueva columna para imágenes
                { key: 'author', title: 'Author' },
                { key: 'content', title: 'Content' },
                { key: 'published_at', title: 'Published At' },
                { key: 'likes', title: 'Likes' },
                { key: 'retweets', title: 'Retweets' },
                { key: 'views', title: 'Views' },
            ],
        };
    },
    computed: {
        ...mapState(tweetStore, {
            tweets: store => store.hydra_member,  // Mapear los tweets desde la API
            totalItems: store => store.hydra_totalItems,  // Total de elementos en la API
            loading: store => store.loading,
        }),
        filteredTweets() {
            // Filtrar tweets según la búsqueda
            if (this.search) {
                return this.tweets.filter(tweet =>
                    tweet.content.toLowerCase().includes(this.search.toLowerCase()) ||
                    tweet.author.toLowerCase().includes(this.search.toLowerCase())
                );
            }
            return this.tweets;
        }
    },
};
</script>

<style scoped>
.v-data-table {
    min-height: 400px;
}

.v-img {
    border-radius: 8px;
    object-fit: cover;
}
</style>