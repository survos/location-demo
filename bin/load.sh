bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load -n
bin/console bordeux:geoname:import -a http://download.geonames.org/export/dump/CA.zip

