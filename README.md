## Requirements

### Redis
you should setup redis for jobs

### Supervisor
supervisor is required for websockets, see a working supervisord.conf in the docker folder.
you just have to replace the paths, also run this as root.
You have to copy the letsencrypt cert to a folder inside the this project with chown to your www-data user.
The .env.example contains an example path, you can keep your certs wherever you want.

### Cronjob
For updating the devices you should setup a cronjob

### MQTT
The device data will be published using a mosquitto mqtt server.

### Meilisearch
Meilisearch is recommended, set your scout driver accordingly

## License

V-Butler is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
