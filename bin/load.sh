rm var/data.db
bin/console doctrine:schema:update --force --dump-sql
# bin/console doctrine:fixtures:load -n
# bin/console bordeux:geoname:import -a http://download.geonames.org/export/dump/US.zip --timezones=0 --download-dir /home/tac/data/bordeux/geoname --env=prod
bin/console bordeux:geoname:import -a http://download.geonames.org/export/dump/MX.zip  --timezones=0 --download-dir /home/tac/data/bordeux/geoname

