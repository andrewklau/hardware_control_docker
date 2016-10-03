# Controlling Hardware via the Cloud

![Overview](/public/images/overview.png "Overview")

This project provides a working concept on how we can use Docker to remotely execute
jobs on machinery (primarily electronic machinery) securely.

Major motives:

- Platform to securely allow students to access remote machinery
- Easily add new machinery, plug and play.
- Allow students to upload and execute untrusted code
- Fine tuned control, limit access to specific hardware functions

## Concept

The project design can be split into two key components, the scheduler and the workers.

The scheduler is a service that is made available in a public cloud, accessible to everyone
that has been authorized to access it. The scheduler provides a web interface for the user
to upload their controls (we refer to as untrusted code) and other instructions such as which
machinery they want to access. The scheduler also determines which access level the user should
gain, ie. should they be allowed to access MachineA or just MachineB.

The workers, comprise of multiple RaspberryPi boards. These workers are connected to a dedicated
piece of machinery, and will regularly query the scheduler for jobs that they have been allocated.
For each job they are allocated, they will execute on their associated machinery and return the
results back to the scheduler for user analysis.

Docker is used to provide the secure execution of code on the worker devices. The worker runs a NodeJS
script to initiate job execution and interact with the Docker daemon.

## Installation

The scheduler is built on the Laravel framework (5.3). Clone this repository into your cloud environment,
the following ENV variables are required to be set:

```
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

You can now run the installation process:

```
composer install
php artisan migrate
```

## Configuration

This project aims to be configuration free, however currently you will need
to manually add the devices and their tokens into the scheduler.

## Security & Permissions

The default security level is to block all GPIO access.

When the worker claims a job it goes through a three step process:

- Run a privileged container to open up the GPIO ports the job has been allocated.
- Run an unprivileged container to execute student code as a standard user (root privileges dropped).
- Run a privileged container to reset the GPIO ports.

The privileged containers use the WiringPi library to open access to GPIO ports.
This is packaged in https://hub.docker.com/r/andrewklau/raspbian-gpio/

Permissions are created through the scheduler interface. These should be set by the lecturer/administrator,
to be allocated to appropriate user. Permissionso use the WiringPi cli syntax (gpio), they should be passed
in a CSV format.

eg. the input `export 1 out,export 0 in` will be executed as:

```
gpio export 1 out
gpio export 0 in
```

## Device/Job API

An API endpoint is exposed at /api/devices and accepts the querystring/parameter/header `api_token`.

Devices interact with this endpoint to get new jobs and post job results/statuses.

## Docker Containers

All Docker containers are built on top of Raspbian Jessie (2016-09-03) - https://hub.docker.com/r/andrewklau/raspbian/

- https://hub.docker.com/r/andrewklau/raspbian-core/ is overlayed to provide core packages
  - https://hub.docker.com/r/andrewklau/raspbian-gpio/ provides the WiringPi GPIO utility
  - https://hub.docker.com/r/andrewklau/raspbian-python/ provides the WiringPi Python libraries

## Future

- Implement broadcast notifications for live streaming of results (pusher)
- Implement level based access for limiting permission scope
- Add device management and token generation (let devices check in automatically)
- Limit user registration (use an OAuth provider instead)
- Add options to select different container workers (eg. now only python is supported)
