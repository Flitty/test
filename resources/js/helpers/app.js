export default {
    logout: function(notify) {
        this.$store.dispatch('logoutUser');
        this.$router.push({name: 'login'});

        if (notify) {
            this.$notify('You are Unauthorized', 'error');
        }
        return true;
    }
}