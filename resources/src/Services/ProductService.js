import {useQuery} from 'react-query';
import {apiClient, errorHandler} from './ApiService';

async function getProducts() {
    const client = await apiClient();

    return client.get("/products")
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

const useGetProducts = () => {
    return useQuery('products', getProducts);
}

export const ProductService = {
    useGetProducts,
}