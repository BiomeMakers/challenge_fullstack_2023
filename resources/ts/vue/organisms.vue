<template>

   <div>

       <h2> Organism list ({{ totalOrganisms }}) </h2>

       <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Genus</th>
                    <th>Species</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="organism of organisms" :key="organism.id">
                    <td>{{ organism.genus }}</td>
                    <td>{{ organism.species }}</td>
                </tr>
            </tbody>
        </table>

        <div class="pure-group">
            <button class="pure-button" @click="previousPage()">Previous</button>
            <button class="pure-button" @click="nextPage()">Next</button>
        </div>

   </div>

</template>

<style lang="scss" scoped >

</style>

<script lang="ts">

import {Vue, Component, Prop, Watch} from 'vue-property-decorator';
import axios from 'axios';

/**
 * Type of the organism
 */
type OrganismT = {
    id: number;
    genus: string;
    species: number;
}

@Component({})
export default class OrganismsVue extends Vue {

    organisms: OrganismT[] = [];
    currentPage: number = 1;
    lastPage: number = 1;
    totalOrganisms: number = 0;

    mounted() {
        this.loadOrganisms();
    }

    async loadOrganisms() {
        // Gets data from endpoint
        const request = (await axios.get('/api/organisms/', {
            params: {
                page: this.currentPage,
            },
        })).data;

        // Save all the relevant data needed for organisms and pagination
        this.organisms = request.data;
        this.totalOrganisms = request.total;
        this.currentPage = request.current_page;
        this.lastPage = request.last_page;
    }

    nextPage() {
        if (this.currentPage < this.lastPage) {
            this.currentPage++;
        }
        // Reload data
        this.loadOrganisms();
    }

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
        }
        // Reload data
        this.loadOrganisms();
    }

}
</script>
