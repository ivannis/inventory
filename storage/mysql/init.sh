#!/bin/bash

function create_database() {
	local database=$1
	echo "  Creating database '$database'"
	mysql -u root --password=$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE $database; GRANT ALL ON $database.* TO '$MYSQL_USER'@'%';"
}

if [ -n "$MYSQL_DATABASES" ]; then
  mysql -u root --password=$MYSQL_ROOT_PASSWORD -e "GRANT SUPER ON *.* TO '$MYSQL_USER'@'%';"
	for db in $(echo $MYSQL_DATABASES | tr ',' ' '); do
		create_database $db
	done
	echo "Databases created sucessful"
fi