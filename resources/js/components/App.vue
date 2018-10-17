<template>
    <div>
        <router-view name="user"></router-view>
        <router-view name="any"></router-view>
        <router-view v-if="guest" name="guest"></router-view>
    </div>
</template>

<script>
    import {interceptors}       from '../helpers/api';
    import AppHelper            from '../helpers/app';

    export default {
        data(){
            return{
                authRoutes: [
                    'login',
                    'register',
                ]
            }
        },
        components: {
        },
        computed: {
            guest(){
                return this.inArray(this.$route.name, this.authRoutes);
            },
            athorization() {
                return this.$store.state.api_token;
            },
            guest() {
                return !this.auth;
            }
        },

        created() {
            // global error http handler
            interceptors((err) => {
                if (this.$store.state.api_token && err.response.status !== 422) {
                    this.$router.push({name: 'home'});
                }

                if (err.response.status === 401) {
                    this.$store.dispatch('logoutUser');
                    this.$router.push({name: 'login'});
                    this.$notify('You are Unauthorized', 'error');
                }

                if (err.response.status === 500) {
                    this.$notify('Something went wrong', 'error');
                }

                if (err.response.status === 404) {
                    if (!err.response.data.message){
                        this.$router.push('/not-found');
                    }
                }
            });
        },
        methods: {
            inArray(needle, haystack) {
                for (let i = 0; i < haystack.length; i++) {
                    if (haystack[i] === needle) return true;
                }
                return false;
            }
        }
    }
</script>