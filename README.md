# backup-db-to-dropbox
Php script to backup collection of mysql databases to dropbox app folder.

Short Documentation
------------

Clone repository:

```sh
$ git clone https://github.com/XRuff/backup-db-to-dropbox.git
```

Install dependecies:

```sh
$ cd backup-db-to-dropbox
$ composer install
```

Create config file from example file:

```sh
$ cp config/config.neon.example config/config.neon
```

Modify config file:

```yaml
# config/config.neon

parameters:
    # Data folder is local temp dir to temporarily store processing mysql dump
    # Script cleans it after uploading to dropbox. Filder must be writable.
    dataFolder: '/data' # mandatory
    dateFormat: Y-m-d-h-i-s # optional, default is Y-m-d-h-i-s
    compress: yes # optional, default is yes

    dropbox:
        # see https://www.dropbox.com/developers/apps/create, create app and then accessTonen.
        # No app key or app secret nor OAuth settings needed
        token: # mandatory
        dateFolders: yes # optional, default is yes
        verbose: no # optional, default is no

    # mandatory Credentials for database access
    database:
        server: 127.0.0.1 # mandatory, mysql server
        user: root # mandatory, mysql user
        password: # optional, mysql password

    # mandatory list of names of databases you want to backup. DB user must have access to them.
    databases:
        - my_database
        - my_second_database
```

Run it:

```sh
$ php index.php
```

or bash script (have to be executable)

```sh
$ ./run.sh
```