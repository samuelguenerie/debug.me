# debug.me

## Prerequisites

- Docker
- Docker compose
- Git and remote access
- debug_me.sql file

## Project installation

```shell
git clone {remote_ssh}
```

```shell
cd debug.me/
```

```shell
docker compose up
```

```shell
docker exec -i debug.me_database mysql -u root -proot debug.me < {path_to_debug_me.sql_file}
```

If directory `app/public/uploads` doesn't exist:

```shell
docker exec -it debug.me_web_server mkdir public/uploads
```

```shell
debug.me_web_server chmod 777 public/uploads
```

## Project launch

```shell
docker compose up
```

The project can be access on http://localhost:8000 and phpmyadmin on http://localhost:8080.

The user passwords are `Test1234`.