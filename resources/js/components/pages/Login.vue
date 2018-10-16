<template>
    <div>
        <div class="login-wrapper">
            <div class="login-container">
                <h2>Login</h2>
                <div class="form-group login-formular">
                    <form @submit.prevent="handleLoginSubmit" autocomplete="nope">
                        <input type="email" id="loginEmails" :class="{'inputHasError': errors.email}" name="email"
                               v-model="form.email" placeholder="Email" autocomplete='email' required>
                        <error-tip :error="errors.email"></error-tip>

                        <div class="password-wrapper">
                            <input type="password" id="loginPassword" class="password-with-eye"
                                   :class="{'inputHasError': errors.password}" name="password" v-model="form.password"
                                   placeholder="Password" autocomplete='current-password' required>
                        </div>

                        <error-tip :error="errors.password"></error-tip>
                        <button type="submit" class="btn btn-default form-btn">Login</button>
                    </form>
                </div>

                <div class="someErrors"></div>

                <div class="login-help">
                    <router-link to="/register">
                        <a class="account-erstellen-link logform-actions">Create Account</a>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import ErrorTip from '../parts/ErrorTip.vue';

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                errors: {
                    email: '',
                    password: ''
                },
            }
        },
        components: {
            'error-tip': ErrorTip,
        },
        computed: {
        },
        methods: {
            handleLoginSubmit: function() {
                this.errors = {
                    email: '',
                    password: ''
                };

                this.$post('api/auth/login', this.form)
                    .then((res) => {
                    if (res.data) {
                        this.$store.dispatch('setToken', res.data.access_token);
                        this.$router.push({name: 'home'});
                    }
                }).catch((err) => {
                    this.$handleErrors(err, this);
                });
            }
        }
    }
</script>

<style scoped>
    .login-wrapper {
        width: 400px;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
        padding: 25px 50px;
        border: 1px solid #000;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .password-wrapper {
        position: relative;
        width: 100%;
        display: inline-block;
    }
    .login-container button {
        background-color: transparent;
        border: 1px solid #000;
        color: #000;
        border-radius: 0;
        margin: 20px auto 0px;
        width: 200px;
        display: block;
    }
    .login-help {
        font-size: 13px;
        text-align: center;
    }
    .login-container input {
        background: transparent;
        padding: 10px;
        border: 0px;
        color: #000;
        width: 100%;
        border-bottom: 1px solid #000;
    }
    .login-help a {
        color: #000;
        text-decoration: underline;
    }
</style>