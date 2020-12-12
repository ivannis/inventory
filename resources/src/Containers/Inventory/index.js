import React from 'react';
import InventoryHistory from "../../Components/Inventory";
import {InventoryService} from '../../Services/InvetoryService'
import Loader from './Loader';

export default function InventoryContainer({ product }) {
    const { data: history, isLoading } = InventoryService.useGetHistory(product.id);

    if (isLoading) {
        return (
            <Loader className='loader'/>
        )
    }

    return (
        <InventoryHistory items={history}/>
    );
}
