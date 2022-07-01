db=loc_demo
echo "drop database if exists $db; create database $db; grant all privileges on database $db to main; ALTER DATABASE $db OWNER TO main "  | sudo -u postgres psql
echo "grant all privileges on database $db to main; "  | sudo -u postgres psql
