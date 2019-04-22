let lang = document.documentElement.lang;
import Lang from 'lang.js';
let trans = new Lang();
trans.setMessages(require('./language'));
trans.setLocale(lang);
export default trans;