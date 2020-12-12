import axios from 'axios';
import {Config} from '../Config';

export const apiClient = async () => {
    const headers = {};

    return axios.create({
        baseURL: Config.API_URL,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            ...headers,
        },
        timeout: 3000,
    });
}

export const errorHandler = (error) => {
    let data = error ? error.response.data : { message: 'Unknown error'};

    return Promise.reject(data);
}