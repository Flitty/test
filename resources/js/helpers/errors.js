export function handleErrors(err, self) {
    if (err.response.status === 422) {
        let errors = err.response.data.errors;
        if (errors) {
            for(let field in errors) {
                self.form[field].error = errors[field];
            }
        } else {
            self.$notify.error(err.response.data.message);
        }
    } else {
        self.$notify.error(err.response.data.message);
    }
}