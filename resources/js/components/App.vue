<template>
    <div>
        <!--<top-menu></top-menu>-->
        <router-view name="user"></router-view>
        <router-view name="any"></router-view>
        <router-view v-if="guest" name="guest"></router-view>

        <!--<modal-lightbox></modal-lightbox>-->
    </div>
</template>

<script>
    import {interceptors}       from '../helpers/api';
    import TopMenu              from './parts/TopMenu.vue';
//    import authMixin            from '../mixins/authMixin';
//
//    import FormWrapper          from './partcials/FormWrapper.vue';
//    import ModalLightbox        from './partcials/ModalLightbox/ModalLightbox.vue';

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
            TopMenu
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
                    this.logout(true);
                }

                if (err.response.status === 500) {
                    this.$notify.error('Something went wrong');
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