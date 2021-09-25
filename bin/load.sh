rm geonames.db
bin/console doctrine:database:drop --force --em=geonames
bin/console doctrine:schema:update --force --dump-sql --em=geonames
bin/console doctrine:fixtures:load -n
# bin/console bordeux:geoname:import -a http://download.geonames.org/export/dump/US.zip --timezones=0 --download-dir /home/tac/data/bordeux/geoname --env=prod
#bin/console bordeux:geoname:import -a http://download.geonames.org/export/dump/MX.zip  --timezones=0 --download-dir /home/tac/data/bordeux/geoname -vvv

