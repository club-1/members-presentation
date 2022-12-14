all: assets/jquery-3.6.2.min.js assets/jquery-ui-1.13.2/jquery-ui.min.css assets/jquery-ui-1.13.2/jquery-ui.min.js vendor

assets:
	mkdir $@

assets/jquery-3.6.2.min.js: | assets
	wget -q https://code.jquery.com/jquery-3.6.2.min.js -O $@

assets/jquery-ui-1.13.2.zip: | assets
	wget -q https://jqueryui.com/resources/download/jquery-ui-1.13.2.zip -O $@

assets/jquery-ui-1.13.2/jquery-ui.min.css: | assets/jquery-ui-1.13.2.zip
	unzip assets/jquery-ui-1.13.2.zip jquery-ui-1.13.2/jquery-ui.min.css -d assets

assets/jquery-ui-1.13.2/jquery-ui.min.js: | assets/jquery-ui-1.13.2.zip
	unzip assets/jquery-ui-1.13.2.zip jquery-ui-1.13.2/jquery-ui.min.js -d assets

vendor: composer.json composer.lock
	composer install
	touch $@

clean:
	rm -r assets
	rm -r vendor

.PHONY: all clean

.INTERMEDIATE: assets/jquery-ui-1.13.2.zip
