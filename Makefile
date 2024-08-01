USERNAME := $(SUDO_USER)
perm:
	sudo usermod -a -G www-data $(USERNAME)
	chown -R $(USERNAME):www-data ./
	chmod -R 775 ./
project-build: perm
	docker-compose build --build-arg USERNAME=$(USERNAME)
project-up: perm project-build
	docker-compose up -d