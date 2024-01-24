# Archon Web Crawler

Run Docker:
```
docker-compose up -d --build
```

Docker container bash:
```
docker exec -it archon-php bash
```

Show crontab:
```
crontab -l
```

Show running jobs:
```
pstree -apl `pidof cron`
```

---

### **PATHS:**

**Cron jobs file:** */docker/php/cron*

**Crawler file:** */app/crawler.php*

**Crawler functions:** */app/functions.php*

**DB settings:** */app/dbOP/settings.ini.php*
