#!/bin/sh

echo "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = 'finansez';" | sudo su -c psql postgres
echo "DROP DATABASE IF EXISTS finansez;" | sudo su -c psql postgres
echo "CREATE DATABASE finansez;" | sudo su -c psql postgres

/vagrant/src/backend/yii migrate --interactive=0