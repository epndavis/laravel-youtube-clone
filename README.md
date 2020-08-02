# Laravel Youtube Clone

Visit the [Live Site](https://yt-clone.epndavis.com/)

This is a project built to practice a variety of programming and designs. These include:
1) Buidling an api and user auth system in Laravel
2) Creating a single page application (SPA)
3) Processing user uploaded data including files
4) Utilising the vue framework to build an interactive app
5) Programming as a test driven development (TDD)
6) Buliding custom javascript libraries 
7) Producing templates and css/sass styles to match design
8) Understanding wepback, npm and composer to develop mantainable minified code

The end goal is to build a youtube clone/replica (not for commerical use) using the processes listed above. This way I have a design which contains certain problems that will be challenging and best test my skills. 

## Documents

* [Technical Specification](https://docs.google.com/document/d/13AS0ugd_75oG9bE2GAgm488JV_VUfXMUENqzIf1y5y4/edit?usp=sharing)

## Requirements

* php 7.4 and up
* [ffmpeg](https://ffmpeg.org/)

## Setup

1) Clone the repository and cd in the root folder
2) run `composer install`
3) run `npm install`
4) Copy the enviroment file `cp .env.example .env` and run `php artisan key:generate`
5) run `php artisan migrate`
