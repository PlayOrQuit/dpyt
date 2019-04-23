const token = document.head.querySelector('meta[name="csrf-token"]');
import axios from 'axios';
export const fetch = (uri, method, data) => {
    return axios({
        method: method,
        url: uri,
        data: data,
        timeout: 30000,
        withCredentials: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : token.content,
            'Content-Type': 'application/json',
        }
    });
}