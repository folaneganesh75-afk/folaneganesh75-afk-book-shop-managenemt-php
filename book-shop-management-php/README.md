# PHP Book Shop Management System
PHP 8.3 + MySQL 8.4 3-tier style web application with Docker, Jenkins, Docker Hub, Kubernetes and Helm.

## Features
- Add, edit, delete books
- Search by title or author
- Category, price and stock management
- Responsive inventory dashboard
- PDO prepared statements and escaped output
- `/health.php` database health endpoint

## Run with Docker Compose
```bash
docker compose up -d --build
docker compose ps
```
Open http://localhost:8085

Stop containers: `docker compose down`
Delete database data (irreversible): `docker compose down -v`

## Build and push Docker image
```bash
docker build -t ganeshfolane75/bookshop-php:v1 .
docker login
docker push ganeshfolane75/bookshop-php:v1
```

## Kubernetes / Helm
```bash
helm upgrade --install bookshop ./helm/book-shop --create-namespace
kubectl get pods
kubectl get svc
```
Open `http://<NODE-IP>:30085` if NodePort is allowed by the network/firewall.

## Jenkins
Create a Jenkins username/password credential with ID `dockerhub-creds` (Docker Hub username and access token). Configure a Pipeline job from your Git repository. The Jenkinsfile runs PHP syntax checks, builds and pushes the image, then runs Helm upgrade.

## Git
```bash
git init
git add .
git commit -m "Initial PHP Book Shop project"
git branch -M main
git remote add origin <YOUR_GITHUB_REPOSITORY>
git push -u origin main
```

## Production hardening
Sample database passwords are for local learning only. Change them before deployment. Add authentication/authorization, TLS, restricted network policies, resource limits, backups, and externally managed secrets before public production use. MySQL init SQL runs only when its data directory is first initialized.