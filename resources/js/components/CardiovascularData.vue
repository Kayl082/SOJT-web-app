<!-- resources/js/components/CardiovascularData.vue -->
<template>
    <div class="cardiovascular-data">
        <h2>Cardiovascular Disease Data</h2>
        
        <div class="filters">
            <select v-model="selectedState" @change="filterByState">
                <option value="">All States</option>
                <option value="CA">California</option>
                <option value="TX">Texas</option>
                <option value="NY">New York</option>
                <!-- Add more states -->
            </select>

            <input 
                type="number" 
                v-model="selectedYear" 
                placeholder="Year"
                @change="filterByYear"
            />
        </div>

        <div v-if="loading" class="loading">Loading...</div>

        <table v-else>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Location</th>
                    <th>Data Value</th>
                    <th>Data Value Type</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in data" :key="item.locationabbr + item.year">
                    <td>{{ item.year }}</td>
                    <td>{{ item.locationdesc }}</td>
                    <td>{{ item.data_value }}</td>
                    <td>{{ item.data_value_type }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import cardiovascularService from '../services/cardiovascularService';

export default {
    name: 'CardiovascularData',
    data() {
        return {
            data: [],
            loading: false,
            selectedState: '',
            selectedYear: null
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.loading = true;
            try {
                const response = await cardiovascularService.getAll();
                this.data = response.data;
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.loading = false;
            }
        },
        async filterByState() {
            if (!this.selectedState) {
                this.fetchData();
                return;
            }
            
            this.loading = true;
            try {
                const response = await cardiovascularService.getByState(this.selectedState);
                this.data = response.data;
            } catch (error) {
                console.error('Error filtering by state:', error);
            } finally {
                this.loading = false;
            }
        },
        async filterByYear() {
            if (!this.selectedYear) {
                this.fetchData();
                return;
            }
            
            this.loading = true;
            try {
                const response = await cardiovascularService.getByYear(this.selectedYear);
                this.data = response.data;
            } catch (error) {
                console.error('Error filtering by year:', error);
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
.cardiovascular-data {
    padding: 20px;
}

.filters {
    margin-bottom: 20px;
}

.filters select,
.filters input {
    margin-right: 10px;
    padding: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}
</style>