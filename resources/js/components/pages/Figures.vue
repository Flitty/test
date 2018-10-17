<template>
    <div>
        <table>
            <thead>
                <th>Name</th>
                <th>Perimeter (m)</th>
                <th>pointCount</th>
            </thead>
            <tbody>
                <tr v-for="(figure, index) in figures">
                    <td>{{ figure.name }}</td>
                    <td>{{ figure.perimeter }}</td>
                    <td>
                        <ul>
                            <li v-for="point in figure.points">{{ 'lat(' + point.latitude + ') - long(' + point.longitude  + ')' }}</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                figures: [],
                orderBy: 'perimeter',
                sortType: 'asc',
                perPage: 20,
                page: 1,
            }
        },
        computed: {
        },
        mounted() {
            this.getFigures();
        },
        methods: {
            getFigures: function() {

                this.$get('/api/figure/index?sortType=' + this.sortType + '&orderBy=' + this.orderBy)
                    .then((res) => {
                        if (res.data) {
                            this.figures = res.data.figures;
                        }
                    }).catch((err) => {
                    this.$handleErrors(err, this);
                });
            },
        }
    }
</script>
<style scoped>
    table, td, th {
        border: 1px solid #000;
    }
    table {
        width: 800px;
    }
</style>