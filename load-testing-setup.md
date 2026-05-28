# Load Testing Setup (Windows)

This guide covers both Docker Engine (via Docker Desktop) and native k6 install.

## Option A: Docker Engine (Docker Desktop)

### 1) Install Docker Desktop

- Download and install Docker Desktop for Windows.
- Enable WSL2 if prompted.
- After install, open Docker Desktop and wait until it says "Docker is running".

### 2) Verify Docker

```powershell
docker --version
```

## Option B: Native k6 (No Docker)

Choose one:

### Winget

```powershell
winget install --id Grafana.k6
```

### Chocolatey

```powershell
choco install k6
```

### Verify k6

```powershell
k6 version
```

## Run the login test

### 1) Set environment variables

```powershell
$env:BASE_URL = "http://127.0.0.1:8000"
$env:USER_LOGIN = "admin"
$env:USER_PASSWORD = "your_password"
```

### 2) Run with native k6

```powershell
k6 run loadtest-login.js
```

### 3) Or run with Docker

```powershell
docker run --rm -v ${PWD}:/work -w /work -e BASE_URL -e USER_LOGIN -e USER_PASSWORD grafana/k6 run loadtest-login.js
```

## Notes

- Use a real test account for USER_LOGIN/USER_PASSWORD.
- Do not commit real credentials to git.
- If you want higher load, update the stages in loadtest-login.js.
