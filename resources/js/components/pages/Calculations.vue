<template>
    <div>
        <div>
            <input id="figure-name" placeholder="Enter the name" v-model="figureName">
            <error-tip :error="figureNameError"></error-tip>
            <h2>Add 3 or more points to count the perimeter</h2>
            <small v-if="perimeter">Current perimeter value - {{ perimeter }}</small>
            <div v-for="(value, index) in this.points">
                <h3>Point #{{ index }}</h3>
                <button v-if="!points[index].validated" class="delete-point" @click="deletePoint(index)">X</button>
                <label :for="'latitude-' + index">Latitude</label>
                <input type="number" min="-90" max="90" :id="'latitude-' + index" v-model="points[index].latitude" :readonly="points[index].validated"/>
                <label :for="'longitude-' + index">Longitude</label>
                <input type="number" min="-180" max="180" :id="'longitude-' + index" v-model="points[index].longitude" :readonly="points[index].validated"/>
            </div>
            <button v-if="isAdditional" class="more-point-button" @click="validate">Validate</button>
            <button v-else class="more-point-button" @click="addMorePoint">Add More</button>
            <button class="submit-button" @click="submit">Save</button>
        </div>
    </div>
</template>

<script>

    import ErrorTip from '../parts/ErrorTip.vue';

    export default {
        data() {
            return {
                figureName: '',
                figureNameError: false,
                perimeter: 0,
                points: [],
                isAdditional: true,

            }
        },
        components: {
            ErrorTip
        },
        computed: {

        },
        methods: {
            validate: function() {
                let indexOfLastElement = this.points.length - 1;
                if (indexOfLastElement < 3) {
                    this.points[indexOfLastElement].validated = true;
                    this.isAdditional = false;
                } else {
                    this.$post('api/figure/validate-point', {points: this.points})
                        .then((res) => {
                        if (res.data.isValid) {
                            this.points = res.data.points;
                            this.perimeter = res.data.perimeter;
                            this.isAdditional = false;
                            this.$notify(res.data.message, 'success');
                        } else {
                            this.$notify(res.data.message, 'error');

                        }
                        }).catch((err) => {

                        this.$handleErrors(err, this);
                    });
                }
            },
            submit: function() {
                if (this.points.length < 3) {
                    this.$notify('You need to add at less 3 points to submit the form', 'error');
                    return;
                } else {
                    let figureData = {
                        perimeter: this.perimeter,
                        name: this.figureName,
                        points: this.points
                    };
                    this.$post('api/figure/store', figureData)
                        .then((res) => {
                            if (res.data) {
                                this.refreshData();
                                this.$notify(res.data.message, 'success');
                            }
                        }).catch((err) => {
                        if (err.response.status === 422 && err.response.data.errors) {
                            this.figureNameError = err.response.data.errors.name
                        } else {
                            this.$notify(err.response.message, 'success');
                        }
                    });
                }
            },
            addMorePoint: function() {
                this.isAdditional = true;
                this.points.push({
                    validated: false,
                    latitude: null,
                    longitude: null,
//                    latitude: 0.0,
//                    longitude: 0.0
                });
            },
            deletePoint: function(index) {
                if (confirm('Are you sure?')) {
                    this.points.splice(index, 1);
                    this.isAdditional = false;
                }
            },
            refreshData: function () {
                this.figureName = '';
                this.points = [
                    {
                        validated: false,
                        latitude: 0,
                        longitude: 0,
                    },
                ];
            },
        },
        mounted() {
            this.refreshData();
        }
    }
</script>
<style scoped>
    .more-point-button {
        width: 45%;
        background-color: black;
        color: #FFF;
        height: 40px;
    }
    .submit-button {
        width: 45%;
        background-color: #FFF;
        color: black;
        height: 40px;
    }
    .delete-point {
        display: flex;
        float: right;
    }
    #figure-name {
        background: transparent;
        padding: 10px;
        border: 0px;
        color: #000;
        width: 100%;
        border-bottom: 1px solid #000;
    }
</style>
