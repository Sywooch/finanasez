#!/bin/sh

echo "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '{{db_name}}';" | sudo su -c psql postgres
echo "DROP DATABASE IF EXISTS {{db_name}};" | sudo su -c psql postgres
echo "CREATE DATABASE {{db_name}};" | sudo su -c psql postgres

{{app_path}}/src/backend/yii migrate --interactive=0