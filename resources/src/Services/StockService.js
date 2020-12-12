import {useQuery, useMutation, useQueryCache} from 'react-query';
import {apiClient, errorHandler} from './ApiService';
import moment from "moment";

async function getStatus(_, productId) {
    const client = await apiClient();

    return client.get("/stock/" + productId + "/status")
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

async function getItems(_, productId) {
    const client = await apiClient();

    return client.get("/stock/" + productId + "/items")
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

async function purchase({ productId, date, ...values }) {
    if (date) {
        values = {
            ...values,
            date: moment(date).format('DD/M/Y')
        }
    }

    const client = await apiClient();

    return client.post("/stock/" + productId + "/purchase", values)
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

async function apply({ productId, quantity }) {
    const client = await apiClient();

    return client.post("/stock/" + productId + "/apply", { quantity })
        .then((response) => response.data)
        .catch(errorHandler)
    ;
}

const useGetStatus = (productId) => {
    return useQuery(['stock', productId], getStatus);
}

const useGetItems = (productId) => {
    return useQuery(['stock.items', productId], getItems);
}

const usePurchase = () => {
    const queryCache = useQueryCache();

    return useMutation(purchase, {
        onSuccess: (_, { productId }) => {
            queryCache.refetchQueries(['stock', productId], { exact: true })
            queryCache.refetchQueries(['stock.items', productId], { exact: true })
            queryCache.refetchQueries(['inventory.history', productId], { exact: true })
        },
    });
}

const useApply = () => {
    const queryCache = useQueryCache();

    return useMutation(apply, {
        onSuccess: (_, { productId }) => {
            queryCache.refetchQueries(['stock', productId], { exact: true })
            queryCache.refetchQueries(['stock.items', productId], { exact: true })
            queryCache.refetchQueries(['inventory.history', productId], { exact: true })
        },
    });
}

export const StockService = {
    useGetStatus,
    useGetItems,
    usePurchase,
    useApply,
}