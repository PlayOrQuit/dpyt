const token = document.head.querySelector('meta[name="csrf-token"]');
import axios from 'axios';
export const fetch = (uri, method, data) => {
    console.log(token);
    return axios({
        method: method,
        url: uri,
        data: data,
        timeout: 3000,
        withCredentials: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : token.content,
            'Content-Type': 'application/json',
        }
    });
}