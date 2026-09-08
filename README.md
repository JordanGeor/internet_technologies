# Internet Technologies – List Management Web Application

A web application developed as a university project for the **Internet Technologies** course at the **Department of Informatics, Ionian University**.

The application provides user authentication and allows registered users to create and manage personal lists and their items through a PHP-based web interface.

## 🔍 Features

- User registration and authentication
- User profile management
- Create and delete personal lists
- Add and remove items from lists
- Search list items
- Export data to YAML
- YouTube search functionality
- Persistent data storage using MySQL

## 🛠 Technologies

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Containerization:** Docker, Docker Compose
- **Dependency:** Symfony YAML

## 📂 Project Structure

```text
internet_technologies/
├── src/
├── Dockerfile
├── docker-compose.yml
├── my_users_db.sql
├── composer.json
├── config.php
├── login.php
├── register.php
├── profile.php
├── lists.php
├── list_items.php
├── search_items.php
├── export_yaml.php
├── youtube_search.php
├── style.css
└── script.js
```

## ▶️ Running the Project

### Requirements

- Docker
- Docker Compose

Clone the repository:

```bash
git clone https://github.com/JordanGeor/internet_technologies.git
cd internet_technologies
```

Build and start the containers:

```bash
docker compose up --build
```

Once the containers are running:

- **Web Application:** `http://localhost:8080`
- **phpMyAdmin:** `http://localhost:8081`

The Docker environment includes the PHP web server, MySQL database, and phpMyAdmin.

To stop the containers:

```bash
docker compose down
```

## 🎓 Academic Context

Developed as a university project for the **Internet Technologies** course at the **Department of Informatics, Ionian University**.

### Team

- **Iordanis Georgiadis**
- **Konstantinos Spendas**
- **Petros Perantonakis**
