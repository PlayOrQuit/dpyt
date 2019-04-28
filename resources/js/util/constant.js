const BASE_URL = document.getElementById("baseUrl").href;
export const URL_API_KEY_CREATE = BASE_URL  + '/admin/api-key/create';
export const URL_API_KEY_GET = BASE_URL  + '/admin/api-key/get';
export const URL_API_KEY_DELETE = BASE_URL + '/admin/api-key/delete';
export const URL_API_KEY_EDIT_PRIMARY = BASE_URL + '/admin/api-key/editPrimary';

export const STATUS_CODE_OK = '01';
export const STATUS_CODE_FIELD_ERROR = '02';
export const STATUS_CODE_DB_ERROR = '03';
export const STATUS_CODE_SERVER_ERROR = '04';