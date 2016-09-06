# RaspberryPi Worker

The Raspberry Pi worker will query the scheduler for pending jobs, claim the job ...

## Installing the Worker

The worker is based on the Raspbian Lite operating system, as of writing the
latest version is 2016-05-27-raspbian-jessie-lite based on Debian Jessie.

```
sudo -i

# Install Docker
curl -sSL get.docker.com | sh

# Install NodeJS
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
apt-get -y install nodejs
```

## Debugging

`docker run --rm --cap-add SYS_RAWIO --device /dev/mem --privileged andrewklau/raspbian-gpio`
(we need privileged to export pins - chown -R root:gpio /sys/class/gpio)

The CAP_SYS_RAWIO capability:
* Allow ioperm/iopl and /dev/port access
* Allow /dev/mem and /dev/kmem acess
* Allow raw block devices (/dev/[sh]d??) acess

You can bypass the entrypoint to enter the container using
`docker run -it --entrypoint=/bin/bash -v /sys:/sys andrewklau/raspbian-python -s`
