FROM andrewklau/raspbian-core

MAINTAINER Andrew Lau <andrew@andrewklau.com>

RUN apt-get update && apt-get install -y \
    python \
    python-dev \
    python-pip \
    python-virtualenv \
    --no-install-recommends

RUN pip install wiringpi pyserial

COPY container-entrypoint /usr/bin/

USER user
WORKDIR /home/user
ENTRYPOINT ["container-entrypoint"]
