# VG-Creator

![License](https://img.shields.io/static/v1?label=license&message=MIT&color=green) ![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/JustGritt/VG-Creator) ![GitHub commit activity](https://img.shields.io/github/commit-activity/m/JustGritt/VG-Creator)
![VG-Creator Landing](https://github.com/JustGritt/VG-Creator/blob/main/src/assets/landing.png?raw=true "VG-Creator Landing")

## What is VG-Creator?

[VG-Creator](#VG-Creator) is a easy to use CMS solution for creating and managing your own videogames blog. It is a SAAS solution like [Wix](https://www.wix.com/) or [Wordpress](https://wordpress.com/) but with a lot of extra features and mainly focused on videogames related content.

> In this project, we were asked to create a CMS from scratch using PHP and no javascript librairies except for jQuery or Graph and WYSIWYG editor. We also wanted to create a simple and clean interface that would be easy to use. We also wanted to create a SAAS application that would be easy to use and easy to manage.

## Table of Contents

1. **Getting Started**
    - [What is VG-Creator ?](#VG-Creator)
    - [Design Patterns](#Design-patterns)
    <br>

2. **Download & Prerequisites**
    - [Download instructions](#Download)
    - [Live Test](https://vgcreator.fr/)
    <br>

3. **Contribute**
    - [Submit bugs and feature requests](#contributing)
    - [Support and donations](#contributing)
    <br>

4. **Donations**
    - [Supporting the project](#supporting-the-project)
    <br>

## Design Patterns

We were asked to implement a few design pattern to make our code more readable and maintainable. We used the following ones:
- 2 [Singleton](https://refactoring.guru/design-patterns/singleton) > Core/Sql.class.php & Core/Routing/Router.class.php
- [Factory](https://refactoring.guru/design-patterns/factory-method) > Core/Oauth
- [Observer](https://refactoring.guru/design-patterns/observer) > Core/Observer in Newsletter.php

## Download instructions

**Installing Docker**

Make sure that you have a recent version of [Docker](https://www.docker.com/get-started/), this project is built with docker containers and it is recommended to have a recent version of docker before running this project.

You can download the latest version of docker from [Docker Hub](https://hub.docker.com/) or using the command below.

```bash
sudo apt-get install docker-ce
```

**You may check your Docker version by running**:

```bash
docker --version 
```

<br>

**Installing dependencies**

There is no hard requirement to run this project, but it is recommended to compile the [SCSS](https://sass-lang.com/install) stylesheets using the command below.

```bash
sass --watch src/scss:www/dist/css
```

## Contributing

If you want to help us improve VG-Creator here's what you can do:

- [Submit bugs and feature requests](#), and help us verify as they are checked in
- Review [source code changes](#)

If you are interested in fixing issues and contributing directly to the code, please join our [discord](#) or join us on our social medias ✨

## Supporting the project

You may support this project via ❤️️ [GitHub](https://github.com/sponsors/JustGritt).
