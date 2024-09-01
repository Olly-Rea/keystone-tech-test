import axios from 'axios';

export default axios.create({
  baseURL: 'http://tech-test.localhost/api/',
  timeout: 1500,
});
