import React, {useEffect} from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import toaster from 'toasted-notes';
import 'toasted-notes/src/styles.css';
import 'react-datepicker/dist/react-datepicker.css';
import DatePicker from '../../../Components/Datepicker';
import {StockService} from '../../../Services/StockService'
import {getErrorMessage} from '../../../Utils'
import validationSchema from './ValidationSchema';
import Loader from './Loader';
import '../Apply/styles.css';

export default function StockPurchaseContainer({ product }) {
    const [purchase, { isLoading, error }] = StockService.usePurchase(product.id)

    useEffect(() => {
        if (error) {
            toaster.notify(<div className="toast-error">{getErrorMessage(error)}</div>, {
                position: 'top-right',
            });
        }
    }, [error]);

    const handlePurchase = async (values) => {
        try {
            await purchase(values);
            const { quantity } = values;
            toaster.notify(quantity + ' products were added to the stock!', {
                position: 'top-right',
            });
        } catch (e) {}
    };

    if (isLoading) {
        return (
            <Loader className='loader'/>
        )
    }

    return (
        <React.Fragment>
            <Formik
                initialValues={{
                    productId: product.id,
                    quantity: null,
                    unit_price: null,
                    date: '',
                }}
                onSubmit={handlePurchase}
                validationSchema={validationSchema()}
            >
                <Form className='form'>
                    <div className="form-item">
                        <label htmlFor="quantity">Quantity:</label>
                        <Field name="quantity"/>
                        <ErrorMessage name="quantity" render={msg => <div className='form-error'>{msg}</div>}/>
                    </div>
                    <div className="form-item">
                        <label htmlFor="quantity">Unit price:</label>
                        <Field name="unit_price"/>
                        <ErrorMessage name="unit_price" render={msg => <div className='form-error'>{msg}</div>}/>
                    </div>
                    <div className="form-item">
                        <label htmlFor="quantity">Date:</label>
                        <DatePicker name="date" />
                        <ErrorMessage name="date" render={msg => <div className='form-error'>{msg}</div>}/>
                    </div>
                    <div className="form-item">
                        <button className='button' type="submit">Submit</button>
                    </div>
                </Form>
            </Formik>
        </React.Fragment>
    );
}
