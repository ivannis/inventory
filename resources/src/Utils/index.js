export const getErrorMessage = (error) => {
    if (error.errors) {
        let errors = '';
        Object.entries(error.errors).forEach(([_key, value]) => errors = errors + value + "\n");

        return errors.substring(0, errors.length - 1);
    }

    return error.message;
}

