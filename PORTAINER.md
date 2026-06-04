# Deploy with Portainer (Git repository stack)

This stack runs **Laravel + Nginx + PHP-FPM + MySQL**. Ollama stays on your LAN machine (not in Docker).

## 1. Push this repo to Git

Portainer clones the repository and builds from `docker-compose.yml` at the repo root.

## 2. Create the stack in Portainer

1. **Stacks** → **Add stack**
2. Build method: **Repository**
3. Repository URL: your Git URL (e.g. `https://github.com/JayXCoder/Web-Portfolio`)
4. Repository reference: `main` (or your branch)
5. **Compose path**: `docker-compose.yml`
6. Enable **Authentication** if the repo is private

## 3. Environment variables (required)

Git deploy **does not** include `.env` (it is gitignored). The stack no longer uses `env_file: .env`.

In Portainer, open your stack → **Editor** → scroll to **Environment variables** and either:

1. **Advanced mode**: paste the full contents of your local `.env` file, or  
2. **Load variables from .env file**: upload your local `.env` (Portainer stores it for this stack only)

Then redeploy. Do **not** expect a `.env` file to exist inside the cloned repo.

Required at minimum:

| Variable | Example | Notes |
|----------|---------|--------|
| `APP_KEY` | `base64:...` | Run `php artisan key:generate --show` locally |
| `APP_URL` | `https://jayxcoder.duckdns.org` | Public site URL |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `DB_HOST` | `mysql` | Service name in compose |
| `DB_DATABASE` | `portfolio` | |
| `DB_USERNAME` | `laravel` | |
| `DB_PASSWORD` | *(strong secret)* | Required — compose fails without it |
| `DB_ROOT_PASSWORD` | *(optional)* | Defaults to `DB_PASSWORD` |
| `APP_PORT` | `8080` | Host port for the site |
| `OLLAMA_HOST` | `192.168.0.215` | LAN IP of Ollama server |
| `OLLAMA_PORT` | `11434` | |
| `OLLAMA_MODEL` | `gemma4:e4b` | Must be pulled on Ollama host |
| `ADMIN_NAME` | `Admin` | Display name for bootstrap admin |
| `ADMIN_EMAIL` | `you@example.com` | Admin login email |
| `ADMIN_PASSWORD` | *(strong secret, 8+ chars)* | Admin login password; synced on each container start |

Docker-specific (set in Portainer, not only locally):

```
DB_HOST=mysql
DB_PORT=3306
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Ollama from containers

Containers must reach your Ollama host on the LAN. Use the host’s **LAN IP** in `OLLAMA_HOST` (not `localhost`).  
`extra_hosts: host.docker.internal:host-gateway` is included for hosts that support it; on Unraid, the LAN IP is usually more reliable.

Verify from the `app` container after deploy:

```bash
docker exec -it <stack>_app_1 wget -qO- http://192.168.0.215:11434/api/tags
```

## 4. Deploy

Click **Deploy the stack**. First build can take several minutes (Composer + Vite + images).

Open: `http://<server-ip>:8080` (or your reverse proxy target port).

## 5. Post-deploy

1. Add `ADMIN_EMAIL` and `ADMIN_PASSWORD` to your stack environment (see table above).
2. Restart the **app** container (or redeploy). Entrypoint runs `php artisan admin:sync` automatically.
3. Log in at `/admin/login`.

To apply a new password from `.env` later: update variables in Portainer → restart **app**.

Manual sync:

```bash
docker exec -it <stack>_app_1 php artisan admin:sync
```

## 6. Reverse proxy (recommended)

Point Traefik / Nginx Proxy Manager / SWAG at `http://<host>:8080` with TLS.

**Important:** `APP_URL` must be **`https://jayxcoder.duckdns.org`** (not `http://`).  
If CSS/JS fail on the domain but work on `:8080`, the site is mixed-content blocked — fix `APP_URL` and redeploy the stack (config cache refreshes on container start).

Ensure the proxy forwards: `X-Forwarded-Proto`, `X-Forwarded-For`, `X-Forwarded-Host`.

## 7. Updates

In Portainer: **Pull and redeploy** (or webhook) after pushing to Git. Migrations run automatically on container start.

## Volumes

| Volume | Purpose |
|--------|---------|
| `mysql_data` | Database |
| `app_storage` | Uploads, logs, framework files |
| `app_bootstrap_cache` | Laravel bootstrap cache |

## Troubleshooting

| Issue | Check |
|-------|--------|
| `env file .../.env not found` | Add variables in Portainer **Environment variables** (see §3); push latest `docker-compose.yml` without `env_file` |
| `composer install` exit code 2 | Pull latest `Dockerfile` (Composer runs on `php:8.2` with `intl`, not `composer:2` alone) |
| 502 / blank page | `docker logs <stack>_web_1` and `<stack>_app_1` |
| DB connection | `DB_HOST=mysql`, passwords match compose |
| Ollama offline in admin | `OLLAMA_HOST` reachable from `app` container |
| Assets 404 | Rebuild stack (Vite runs at image build) |
| `APP_KEY` missing | Set in Portainer env before first deploy |
