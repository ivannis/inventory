import React, {useEffect} from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import toaster from 'toasted-notes';
import 'toasted-notes/src/styles.css';
import {StockService} from '../../../Services/StockService'
import {getErrorMessage} from '../../../Utils'
import validationSchema from './ValidationSchema';
import Loader from './Loader';
import './styles.css';

export default function StockApplyContainer({ product }) {
    const [apply, { isLoading, error }] = StockService.useApply(product.id)

    useEffect(() => {
        if (error) {
            toaster.notify(<div className="toast-error">{getErrorMessage(error)}</div>, {
                position: 'top-right',
            });
        }
    }, [error]);

    const handleApply = async (values) => {
        try {
            const data = await apply(values);
            toaster.notify(<div>Hi there, the value of your purchase is <strong>{data.total}</strong> NZD</div>, {
                position: 'top-right',
                duration: null
            });
        } catch (e) {
        }
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
                }}
                onSubmit={handleApply}
                validationSchema={validationSchema()}
            >
                <Form className='form'>
                    <div className="form-item">
                        <label htmlFor="quantity">Quantity:</label>
                        <Field name="quantity"/>
                        <ErrorMessage name="quantity" render={msg => <div className='form-error'>{msg}</div>}/>
                    </div>
                    <div className="form-item">
                        <button className='button' type="submit">Submit</button>
                    </div>
                </Form>
            </Formik>
        </React.Fragment>
    );
}
