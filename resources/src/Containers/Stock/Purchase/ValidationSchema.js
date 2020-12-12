import * as yup from 'yup';

const validationSchema = () => {
    return yup.object().shape({
        quantity: yup
            .number()
            .typeError('Quantity must be a valid number')
            .required()
            .positive('Quantity must be a positive number')
            .integer('Quantity must be an integer')
        ,
        unit_price: yup
            .number()
            .typeError('Quantity must be a valid number')
            .required()
            .positive('Quantity must be a positive number')
        ,
    });
}

export default validationSchema;

