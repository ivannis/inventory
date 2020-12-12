import {useQuery} from 'react-query';
import {apiClient, errorHandler} from './ApiService';

async function getHistory(_, productId) {
    const client = await apiClient();

    return client.get("/inventory/" + productId + "/history")
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

const useGetHistory = (productId) => {
    return useQuery(['inventory.history', productId], getHistory);
}

export const InventoryService = {
    useGetHistory,
}