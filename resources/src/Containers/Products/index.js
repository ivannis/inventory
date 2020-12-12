import React from 'react';
import ProductList from '../../Components/Product';
import {ProductService} from '../../Services/ProductService'
import Loader from './Loader';

export default function ProductsContainer({ onSelected }) {
    const { data: products, isLoading } = ProductService.useGetProducts();

    const onClickProduct = (product) => {
        if (onSelected) {
            onSelected(product)
        }
    }

    if (isLoading) {
        return (
            <Loader className='loader'/>
        )
    }

    return (
        <ProductList products={products} onClick={onClickProduct}/>
    );
}
