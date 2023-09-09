# Identity Provider

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![GitHub Issues](https://img.shields.io/github/issues/thetestcoder/IdentityProvider.svg)](https://github.com/thetestcoder/IdentityProvider/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/thetestcoder/IdentityProvider.svg)](https://github.com/thetestcoder/IdentityProvider/pulls)

Welcome to the Identity Provider repository! We appreciate your interest in contributing. This guide will help you get started with contributing to our project. Please take a moment to review this document before you begin.
This is a Identity Provider project configured with Passport for centralized identity management. It provides user registration, authentication, password reset, and password change functionality.

## Table of Contents

- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Fork the Repository](#fork-the-repository)
    - [Clone Your Fork](#clone-your-fork)
    - [Create a Branch](#create-a-branch)
    - [Installation](#installation)
- [Making Changes](#making-changes)
    - [Coding Guidelines](#coding-guidelines)
    - [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Community and Communication](#community-and-communication)
- [License](#license)

## Getting Started
Before you start contributing, you will need to set up your development environment and create a copy of the project on your local machine.

### Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP >= 8.1
- Composer (https://getcomposer.org/)

### Fork the Repository

Click the "Fork" button at the top-right corner of the repository's page. This will create a copy of the repository in your GitHub account.

### Clone Your Fork

Clone the repository from your GitHub account to your local machine. Replace `thetestcoder` with your GitHub username 
and `IdentityProvider` with the name of the repository.

### Create A Branch

Create a new branch for your contribution. Use a descriptive branch name that reflects the purpose of your changes.
```bash
   git checkout -b feature/new-feature
```

### Installation

There are two ways to install this project. 

#### Via Traditional Way of setting up laravel.

1. Install Dependencies using Composer :

   ```bash
   composer install
    ```

2. Copy .env.example to .env:

   ```bash
   cp .env.example .env
    ```

3. Generate App Key:

   ```bash
   php artisan key:generate
    ```

4. Run the database migrations:

   ```bash
   php artisan migrate
    ```
5. Install Laravel Passport:
    ```bash
    php artisan passport:install
    ```
   
#### Via Docker

1. Run Makefile :

   ```bash
   make init
    ```

## Making Changes

Now you can start making changes to the codebase.

### Coding Guidelines
Follow our coding guidelines and best practices. You can find these guidelines in the CONTRIBUTING.md file.

### Testing
Make sure to test your changes thoroughly. If there are existing tests, run them to ensure that your modifications do not break any existing functionality. If needed, add new tests for the changes you've made.

## Submitting Changes
Once you've made your changes and tested them, it's time to submit your contribution.

### Commit your Changes

```bash
  git add .
  git commit -m "Add your commit message here"
```

### Push your Changes
```bash
  git push origin feature/new-feature
```

### Create a Pull Request
Navigate to the original repository on GitHub and click the "New Pull Request" button. Provide a clear and concise description of your changes in the pull request, and submit it.

## License
This project is licensed under the MIT License 

