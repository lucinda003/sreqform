import http from 'k6/http';
import { check, sleep } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';
const USER_LOGIN = __ENV.USER_LOGIN || '';
const USER_PASSWORD = __ENV.USER_PASSWORD || '';

export const options = {
  stages: [
    { duration: '1m', target: 50 },
    { duration: '1m', target: 75 },
    { duration: '1m', target: 150 },
    { duration: '30s', target: 0 },
  ],
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<1200'],
  },
};

export default function () {
  const loginPage = http.get(`${BASE_URL}/login`);
  if (!loginPage || !loginPage.body) {
    sleep(1);
    return;
  }

  const tokenMatch = loginPage.body.match(/name="_token" value="([^"]+)"/);
  const csrfToken = tokenMatch ? tokenMatch[1] : '';

  const payload = {
    login: USER_LOGIN,
    password: USER_PASSWORD,
    _token: csrfToken,
  };

  const res = http.post(`${BASE_URL}/login`, payload, {
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  });

  check(res, {
    'login status 302 or 200': (r) => r.status === 302 || r.status === 200,
  });

  sleep(1);
}
