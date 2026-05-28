# k6 Login Load Test via Docker Engine

This guide shows a basic stretch test for the Laravel login page using k6 inside Docker Engine.

## Prerequisites

- Docker Engine installed and running.
- Your Laravel app is running, e.g. http://127.0.0.1:8000.
- A valid user account to test login.

## 1) Create k6 script

Save as loadtest-login.js in the project root:

```javascript
import http from 'k6/http';
import { check, sleep } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';
const USER_LOGIN = __ENV.USER_LOGIN || '';
const USER_PASSWORD = __ENV.USER_PASSWORD || '';

export const options = {
  stages: [
    { duration: '2m', target: 1 },
  ],
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<1200'],
  },
};

export default function () {
  const loginPage = http.get(`${BASE_URL}/login`);
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
```

## 2) Run with Docker Engine

PowerShell:

```powershell
$env:BASE_URL = "http://127.0.0.1:8000"
$env:USER_LOGIN = "admin"
$env:USER_PASSWORD = "your_password"

docker run --rm -v ${PWD}:/work -w /work -e BASE_URL -e USER_LOGIN -e USER_PASSWORD grafana/k6 run loadtest-login.js
```

## Notes

- This uses the same login field as the app (username or email).
- If you want more users later, update the stages.
- Avoid committing real passwords. Use environment variables only for local testing.
