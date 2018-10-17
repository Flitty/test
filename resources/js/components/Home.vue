<template>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <router-link to="/">
                    <a>Home</a>
                </router-link>
                <router-link to="/calculations">
                    <a>Calculations</a>
                </router-link>
                <router-link to="/figures">
                    <a>Figures</a>
                </router-link>

                <router-link to="/profile">
                    <a>Profile</a>
                </router-link>
                <span>
                    {{ $store.state.user_name }}
                    <img v-if="$store.state.user_avatar !== 'null'" id="avatar-image" :src="$store.state.user_avatar">
                </span>
                <a class="logout-button" @click="logout">logout</a>
            </div>
        </nav>
        <div class="welcome flex-center position-ref">
            <div class="content">
                <div class="title m-b-md"  v-if="this.$route.name == 'home'">
                    Welcome back, {{ $store.state.user_name }}!
                </div>
                <router-view v-else></router-view>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        computed: {

        },
        mounted() {
            this.getProfile();
        },
        methods: {
            logout: function() {
                this.$store.dispatch('logoutUser');
                this.$router.push({name: 'login'});
                this.$notify('You are Unauthorized', 'error');
            },
            getProfile: function () {
                this.$store.dispatch('setUserData');
                this.$get('/api/profile')
                    .then((res) => {
                        if (res.data) {
                            this.$store.dispatch('setUserData', res.data.user);
                        }
                    }).catch((err) => {
                    if (err.response.data && err.response.data.message) {
                        this.$notify(err.response.data.message);
                    }
                });
            }
        }
    }
</script>
<style scoped>
    a {
        color: #000;
        text-decoration:underline;
    }
    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
    #avatar-image {
        margin-left: 10px;
        max-width: 50px;
        max-heugnt: 50px;
    }
    .logout-button {
        cursor: pointer;
        text-decoration: underline!important;
    }
</style>