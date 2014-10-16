Medical Media Men Project
=========================

Setup
-----

#### Clone the repo
```
git clone git@github.com:ekweible/461-project.git
```

#### Install all php dependencies
```
cd 461-project
php composer.phar self-update
php composer.phar install
```

#### MAMP
[Download MAMP](http://www.mamp.info/en/mamp_windows.html) for easily spinning up a local php/mysql server

- Open MAMP
- Open "Preferences" > "Web Server"
- Change the "Document Root" to point to the `461-project/public/` directory

#### MySQL database configuration
- Start the MAMP servers
- Go to [phpMyAdmin](http://localhost:8888/MAMP/index.php?page=phpmyadmin&language=English)
- Setup tables (I'll add more on this later)

#### Run App
- Start the MAMP servers
- Go to [http://localhost:8888](http://localhost:8888)
