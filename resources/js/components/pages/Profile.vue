<template>
    <div>
        <div class="profile-wrapper">
            <div class="profile-container">
                <h2>Profile</h2>
                <div class="form-group profile-formular">
                    <form @submit.prevent="handleProfileSubmit">
                        <input :class="{'inputHasError': form.name.error}"
                               v-model="form.name.temp" placeholder="Name" required>
                        <error-tip :error="form.name.error"></error-tip>

                        <input type="email" :class="{'inputHasError': form.email.error}"
                               v-model="form.email.temp" placeholder="Email" required>
                        <error-tip :error="form.email.error"></error-tip>

                        <input type="password" :class="{'inputHasError': form.password.error}"
                               v-model="form.password.temp" placeholder="Enter new password" >
                        <error-tip :error="form.password.error"></error-tip>

                        <div class="avatar-form-field">
                            <label for="avatar-field">Enter src manually</label>
                            <input id="avatar-field" :class="{'inputHasError': form.avatar.error}"
                                   v-model="form.avatar.temp" placeholder="Enter image url" >
                            <label for="avatar-file-field">or Upload the file</label>
                            <input v-if="form.avatar_file.withFile" type="button" @click="removeImage" value="Cancel">
                            <input v-else @change="onFileChange" id="avatar-file-field" type="file" :class="{'inputHasError': form.avatar_file.error}"
                                    placeholder="Avatar" >
                            <img id="preview-avatar-image" :src="form.avatar_file.preview || form.avatar.temp">
                        </div>
                        <error-tip :error="form.avatar.error"></error-tip>
                        <error-tip :error="form.avatar_file.error"></error-tip>

                        <input type="date" :class="{'inputHasError': form.birthday.error}"
                               v-model="form.birthday.temp" placeholder="Birthday" >
                        <error-tip :error="form.birthday.error"></error-tip>
                        <span class="select-form-item">
                            <label>Gender</label>
                            <select v-model="form.gender.temp">
                                <option value="0">Woman</option>
                                <option value="1">Man</option>
                            </select>
                            <error-tip :error="form.gender.error"></error-tip>
                        </span>
                        <span class="select-form-item">
                            <label>Do you want to receive news messages?</label>
                            <select v-model="form.news_mailing.temp">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <error-tip :error="form.news_mailing.error"></error-tip>
                        </span>
                        <span class="select-form-item">
                            <label>Your biography . . .</label>
                            <textarea v-model="form.biography.temp"></textarea>
                            <error-tip :error="form.biography.error"></error-tip>
                        </span>

                        <!--<error-tip :error="errors.password"></error-tip>-->
                        <button type="submit" class="btn btn-default form-btn">Update</button>
                    </form>
                </div>

                <div class="someErrors"></div>

                <div class="profile-help">
                    <!--<router-link to="/register">-->
                        <a class="account-erstellen-link logform-actions" @click="cancelUpdating">Cancel</a>
                    <!--</router-link>-->
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
                    name: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    password: {
                        current: '',
                        temp: null,
                        error: false
                    },
                    email: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    avatar: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    avatar_file: {
                        withFile: false,
                        preview: '',
                        current: '',
                        temp: null,
                        error: false
                    },
                    birthday: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    gender: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    news_mailing: {
                        current: '',
                        temp: '',
                        error: false
                    },
                    biography: {
                        current: '',
                        temp: '',
                        error: false
                    },
                }
            }
        },
        components: {
            ErrorTip
        },
        computed: {
        },
        methods: {
            cancelUpdating: function() {
                for (let key in this.form) {
                    this.form[key].temp = this.form[key].current;
                }
            },
            getProfileData: function() {
                this.$get('/api/profile')
                    .then((res) => {
                        if (res.data) {
                            let user = res.data.user;
                            for (let key in this.form) {
                                this.form[key].current = user[key];
                                this.form[key].temp = user[key];
                            }
                            this.form.avatar_file.temp = null;
                            this.form.password.temp = null;

                        }
                    }).catch((err) => {
                    this.$handleErrors(err, this);
                });
            },
            handleProfileSubmit: function() {
                let profileData = new FormData();
                for (let field in this.form) {
                    let value = this.form[field].temp;
                    if (value && value !== undefined) {
                        profileData.append(field, value);
                    }
                }
                profileData.append('_method', 'put');
                this.$post('/api/profile/update', profileData)
                    .then((res) => {
                        if (res.data) {
                            this.$store.dispatch('setToken', res.data.access_token);
                            let user = res.data.user;

                            this.$store.dispatch('setUserData', user);
                            for (let key in this.form) {
                                this.form[key].current = user[key];
                                this.form[key].temp = user[key];
                            }
                        }
                    }).catch((err) => {
                        this.$handleErrors(err, this);
                    });
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.form.avatar_file.temp = files[0];
                this.createImage(files[0]);
            },
            createImage(file) {
                let image = new Image();
                let reader = new FileReader();
                let vm = this;

                reader.onload = (e) => {
                    vm.form.avatar_file.preview = e.target.result;
                    vm.form.avatar_file.withFile = true;
                };
                reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                this.form.avatar_file.preview = '';
                this.form.avatar_file.withFile = false;
                this.form.avatar_file.temp = null
            },
        },
        mounted() {
            this.getProfileData()
        }
    }
</script>
<style scoped="">
    .profile-wrapper {
        width: 550px;
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
    .profile-container button {
        background-color: transparent;
        border: 1px solid #000;
        color: #000;
        border-radius: 0;
        margin: 20px auto 0px;
        width: 200px;
        display: block;
    }
    .profile-help {
        font-size: 13px;
        text-align: center;
    }
    .profile-container input {
        background: transparent;
        padding: 10px;
        border: 0px;
        color: #000;
        width: 100%;
        border-bottom: 1px solid #000;
    }
    .profile-help a {
        color: #000;
        text-decoration: underline;
    }
    .select-form-item select {
        width: 100%;
    }
    .select-form-item textarea {
        width: 100%;
    }
    .select-form-item label {
        margin-top: 10px;
    }
    .avatar-form-field input, .avatar-form-field label {
        width: 70%;
        float: left;
    }
    .avatar-form-field img {
        max-width: 100px;
        max-height: 100px;
        margin: 20px 10px;
    }
</style>
