USERNAME := $(SUDO_USER)
perm:
	chown -R $(USERNAME):www-data ./
	chmod -R 775 ./
project-up: perm
	docker-compose up -d