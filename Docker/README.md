# RaspberryPi Worker

The Raspberry Pi worker will query the scheduler for pending jobs, claim the job and post back results.

The worker will poll the scheduler every minute for available jobs.

## Installing the Worker

The worker is based on the Raspbian Lite operating system, as of writing the
latest version is 2016-05-27-raspbian-jessie-lite based on Debian Jessie.

```
sudo -i

# Install Docker
curl -sSL get.docker.com | sh

# Get the Docker images
docker pull andrewklau/raspbian-python
docker pull andrewklau/raspbian-gpio

# Install NodeJS
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
apt-get -y install nodejs

git clone https://github.com/andrewklau/hardware_control_docker
cd hardware_control_docker/Docker/worker
npm install --unsafe-perm
```

The worker service is a NodeJS script which queries the scheduler for jobs every minute.
It should be started on boot. The worker requires the following ENV variables:

- SCHEDULER_URL
- SCHEDULER_TOKEN

Setting the NodeJS script to start on boot will be done with systemd:

```
echo '
[Unit]
Description=Cloud Hardware Control (node-worker)
After=docker.service
Requires=docker.service

[Service]
ExecStart=/usr/bin/node /root/hardware_control_docker/Docker/worker/index.js
Restart=always
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=node-worker
User=root
Group=root
Environment=SCHEDULER_URL= SCHEDULER_TOKEN=

[Install]
WantedBy=multi-user.target ' > /etc/systemd/system/node-worker.service

systemctl enable node-worker
systemctl start node-worker
```

RaspberryPi 3 now comes with an inbuilt Wifi adapter! This means we can easily connect this
to the corporate network:

```
# Find the Wifi SSID
iwlist wlan0 scan

echo 'network={
    ssid="testing"
    psk="testingPassword"
}' >> /etc/wpa_supplicant/wpa_supplicant.conf

# wpa-supplicant should notice the change and connect automatically
```

## Debugging

`docker run --rm --cap-add SYS_RAWIO --device /dev/mem --privileged andrewklau/raspbian-gpio`
(we need privileged to export pins - chown -R root:gpio /sys/class/gpio)

The CAP_SYS_RAWIO capability:
* Allow ioperm/iopl and /dev/port access
* Allow /dev/mem and /dev/kmem acess
* Allow raw block devices (/dev/[sh]d??) acess

You can bypass the entrypoint to enter the container using
`docker run -it --entrypoint=/bin/bash -v /sys:/sys --cap-add SYS_RAWIO --device /dev/mem --privileged andrewklau/raspbian-gpio -s`
`docker run -it --entrypoint=/bin/bash -v /sys:/sys andrewklau/raspbian-python -s`

## Future

- Implement an automated installation (gold image with token generation)
- Automatically update the node-worker (move to Docker container)
- Implement a timeout to prevent tasks from executing forever
