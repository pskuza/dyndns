# dyndns
[![Build Status](https://travis-ci.org/pskuza/dyndns.svg?branch=master)](https://travis-ci.org/pskuza/dyndns)
[![](https://images.microbadger.com/badges/image/pskuza/dyndns:master.svg)](https://hub.docker.com/r/pskuza/dyndns/)


* Sqlite3 database. (https://github.com/paragonie/easydb) 
* Memory based cache. (https://github.com/doctrine/cache)

## Install

``` sh
docker pull pskuza/dyndns
```
Will pull the latest tagged release from the docker hub.

This image is meant to be run behind an nginx instance acting as a reverse proxy.

``` sh
docker run -d --restart unless-stopped -v /your/volume/path:/data --name dyndns dyndns
```

Edit your nginx running on the host... TODO 

hint: proxy_set_header        X-Real-IP       $remote_addr;