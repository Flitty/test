<template>
    <div class="error-tip" v-show="error">{{ errors }}</div>
</template>
<script>
    export default {
        props:['error'],
        computed: {
            errors() {
                let errors = '';
                for (let index in this.error) {
                    errors += this.error[index] + ' ';
                }
                return errors;
            }
        }
    }
</script>
<style scoped>
    .error-tip {
        color: red;
    }
</style>