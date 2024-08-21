<template>
    <div id="tag-cloud" style="width: 100%; height: 400px;"></div>
</template>

<script>
import * as d3 from 'd3';
import cloud from 'd3-cloud';
import { mapState } from 'pinia';
import { tweetStore } from '../stores/tweet';

export default {
    name: 'TagCloudChart',
    computed: {
        ...mapState(tweetStore, {
            tweets: store => store.hydra_member,
            loaded: store => store.loaded,
        }),
        tags() {
            const hashtagCounts = {};
            this.tweets.forEach(tweet => {
                tweet.hashtags.forEach(hashtag => {
                    hashtagCounts[hashtag] = (hashtagCounts[hashtag] || 0) + 1;
                });
            });
            return Object.keys(hashtagCounts).map(hashtag => ({
                text: hashtag,
                size: hashtagCounts[hashtag],
            }));
        }
    },
    watch: {
        tags(newValue) {
            this.drawTagCloud();
        }
    },
    mounted() {
        if (this.loaded) this.drawTagCloud();
    },
    methods: {
        drawTagCloud() {
            const width = this.$el.clientWidth;
            const height = 400;

            const layout = cloud()
                .size([width, height])
                .words(this.tags.map(d => ({ text: d.text, size: d.size * 10 })))
                .padding(5)
                .rotate(() => ~~(Math.random() * 2) * 90)
                .font('Impact')
                .fontSize(d => d.size)
                .on('end', this.draw);

            layout.start();
        },
        draw(words) {
            const width = this.$el.clientWidth;
            const height = 400;

            d3.select(this.$el).append('svg')
                .attr('width', width)
                .attr('height', height)
                .append('g')
                .attr('transform', `translate(${width / 2},${height / 2})`)
                .selectAll('text')
                .data(words)
                .enter().append('text')
                .style('font-size', d => `${d.size}px`)
                .style('font-family', 'Impact')
                .style('fill', () => d3.schemeCategory10[Math.floor(Math.random() * 10)])
                .attr('text-anchor', 'middle')
                .attr('transform', d => `translate(${[d.x, d.y]})rotate(${d.rotate})`)
                .text(d => d.text);
        }
    }
};
</script>