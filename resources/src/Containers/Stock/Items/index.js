import React from 'react';
import {StockService} from '../../../Services/StockService'
import StockItems from "../../../Components/Stock/Items";
import Loader from './Loader';

export default function StockItemsContainer({ product }) {
    const { data: items, isLoading } = StockService.useGetItems(product.id);

    if (isLoading) {
        return (
            <Loader className='loader'/>
        )
    }

    return (
        <StockItems items={items}/>
    );
}
