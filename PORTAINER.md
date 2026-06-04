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

## 3. Environment variables

Either:

- Paste the contents of `.env.example` into Portainer’s **Environment variables** editor and fill in values, or  
- Use **Load variables from .env file** and upload your `.env`

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

Create an admin user (once):

```bash
docker exec -it <stack>_app_1 php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin','email'=>'you@example.com','password'=>bcrypt('your-password'),'role'=>'admin','is_active'=>true]);
```

Optional seed:

```bash
docker exec -it <stack>_app_1 php artisan db:seed
```

## 6. Reverse proxy (recommended)

Point Traefik / Nginx Proxy Manager / SWAG at `http://<host>:8080` with TLS. Set `APP_URL` to the HTTPS URL.

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
| 502 / blank page | `docker logs <stack>_web_1` and `<stack>_app_1` |
| DB connection | `DB_HOST=mysql`, passwords match compose |
| Ollama offline in admin | `OLLAMA_HOST` reachable from `app` container |
| Assets 404 | Rebuild stack (Vite runs at image build) |
| `APP_KEY` missing | Set in Portainer env before first deploy |
