import axios from 'axios';
window.axios = axios;
import '../sass/app.scss';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
