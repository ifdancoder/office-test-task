USERNAME := $(shell whoami)

ifeq ($(USERNAME),root)
    USERNAME := $(shell sudo -u $(SUDO_USER) whoami)
endif
perm:
	sudo usermod -aG www-data $(USERNAME)
	sudo chown -R $(USERNAME):www-data ./
	sudo chmod -R 775 ./
project-build: perm
	docker-compose build --build-arg USERNAME=$(USERNAME)
project-build-up: perm project-build
	docker-compose up -d
project-up: perm
	docker-compose up -d