<template>
    <div>
       HOME
    </div>
</template>

<script>

    export default {
        data() {
            return {
                user: {
                    name: '',
                    avatar: ''
                }
            }
        },
        computed: {
        },
        mounted() {
            this.getProfile();
        },
        methods: {
            getProfile: function () {
                this.$store.dispatch('setUserData');
                if (!this.$store.state.user_name || !this.$store.state.user_avatar) {
                    this.$get('/api/profile')
                        .then((res) => {
                            if (res.data) {
                                this.$store.dispatch('setUserData', res.data.user);
                            }
                        }).catch((err) => {
//                    if (err.response.status === 422) {
//                        if (err.response.data.errors) {
//                            handleErrors(err.response.data.errors, this.errors, self, true);
//                        } else {
//                            self.$notify.error(err.response.data.message);
//                        }
//                    } else {
//                        self.$notify.error(err.response.data.message);
//                    }

                        this.isProcessing = false
                    });
                }
            }
        }
    }
</script>
<!--<template>-->
    <!--<div>-->
        <!--<language-button></language-button>-->

        <!--<div id="oc-logo-login">-->
            <!--<img src="../../sass/images/oclogo-big.png"/>-->
        <!--</div>-->

        <!--<div class="login-container">-->
            <!--<div id="loginStatus"></div>-->

            <!--<div class="form-group login-formular">-->
                <!--<form @submit.prevent="handleLoginSubmit" autocomplete="nope">-->
                    <!--<input type="email" id="loginEmails" :class="{'inputHasError': errors.email}" name="email" v-model="form.email" :placeholder="emailAddress" autocomplete='email' required>-->
                    <!--<error-tip :error="errors.email"></error-tip>-->

                    <!--<div class="password-wrapper">-->
                        <!--<input :type="((!showPassword)?'password':'text')" id="loginPassword" class="password-with-eye" :class="{'inputHasError': errors.password}" name="password" v-model="form.password" :placeholder="myPassword" autocomplete='current-password' required>-->
                        <!--<span v-show="!showPassword" class="eye-icon glyphicon glyphicon-eye-open" @click="openPassword"></span>-->
                        <!--<span v-show="showPassword" class="eye-icon glyphicon glyphicon-eye-close" @click="closePassword"></span>-->
                    <!--</div>-->

                    <!--<error-tip :error="errors.password"></error-tip>-->
                    <!--<button type="submit" class="btn btn-default form-btn" v-lang.login></button>-->
                <!--</form>-->
            <!--</div>-->

            <!--<div class="someErrors"></div>-->

            <!--<div class="login-help">-->
                <!--<router-link to="/register">-->
                    <!--<a class="account-erstellen-link logform-actions" v-lang.createAccount></a>-->
                <!--</router-link>-->
                <!--<router-link to="/reset-password">-->
                    <!--<a class="account-erstellen-link logform-actions" v-lang.forgotPassword></a>-->
                <!--</router-link>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</template>-->

<!--<script>-->
    <!--import {EventBus}           from '../helpers/EventBus';-->
    <!--import {handleErrors}       from '../helpers/errors'-->
    <!--import authMixin            from '../mixins/authMixin';-->
    <!--import LanguageButton       from './partcials/LanguageButton';-->
    <!--import ErrorTip             from './partcials/ErrorTip';-->

    <!--export default {-->
        <!--data() {-->
            <!--return {-->
                <!--form: {-->
                    <!--email: '',-->
                    <!--password: ''-->
                <!--},-->
                <!--errors: {-->
                    <!--email: '',-->
                    <!--password: ''-->
                <!--},-->
                <!--isProcessing: false,-->
                <!--showPassword: false,-->
            <!--}-->
        <!--},-->
        <!--computed: {-->
            <!--emailAddress() {-->
                <!--return this.translate('email');-->
            <!--},-->
            <!--myPassword() {-->
                <!--return this.translate('password');-->
            <!--}-->
        <!--},-->
        <!--components: {-->
            <!--LanguageButton,-->
            <!--ErrorTip-->
        <!--},-->
        <!--mixins: [-->
            <!--authMixin-->
        <!--],-->
        <!--methods: {-->
            <!--openPassword() {-->
                <!--this.showPassword = true;-->
            <!--},-->
            <!--closePassword() {-->
                <!--this.showPassword = false;-->
            <!--},-->
            <!--handleLoginSubmit() {-->
                <!--this.errors = {-->
                    <!--email: '',-->
                    <!--password: ''-->
                <!--};-->

                <!--this.isProcessing = true;-->
                <!--let self = this;-->

                <!--this.$post('api/v1/login', this.form).then((res) => {-->
                    <!--if (this.login(res.data)) {-->
                        <!--this.$router.push(this.$store.getters.lastRoute);-->
                    <!--}-->

                    <!--this.isProcessing = false;-->
                <!--}).catch((err) => {-->
                    <!--if (err.response.status === 422) {-->
                        <!--if (err.response.data.errors) {-->
                            <!--handleErrors(err.response.data.errors, this.errors, self, true);-->
                        <!--} else {-->
                            <!--self.$notify.error(err.response.data.message);-->
                        <!--}-->
                    <!--} else {-->
                        <!--self.$notify.error(err.response.data.message);-->
                    <!--}-->

                    <!--this.isProcessing = false-->
                <!--});-->
            <!--}-->
        <!--}-->
    <!--}-->
<!--</script>-->