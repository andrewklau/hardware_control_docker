FROM andrewklau/raspbian-core

MAINTAINER Andrew Lau <andrew@andrewklau.com>

RUN apt-get update && apt-get -y install sudo

RUN git clone git://git.drogon.net/wiringPi && \
    cd wiringPi && \
    ./build

COPY container-entrypoint /usr/bin/

ENTRYPOINT ["container-entrypoint"]
