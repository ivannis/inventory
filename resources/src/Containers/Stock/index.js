import React from 'react';
import {StockService} from '../../Services/StockService'
import StockStatus from '../../Components/Stock';
import Loader from './Loader';

export default function StockContainer({ product }) {
    const { data: stock, isLoading } = StockService.useGetStatus(product.id);

    if (isLoading) {
        return (
            <Loader className='loader'/>
        )
    }

    return (
        <StockStatus stock={stock}/>
    );
}
