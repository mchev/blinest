import _ from 'lodash';
window._ = _;

import __ from './translation';
window.__ = __;

import axios from 'axios'
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

import './echo';
