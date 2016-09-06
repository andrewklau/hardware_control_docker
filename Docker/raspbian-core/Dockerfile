FROM andrewklau/raspbian

MAINTAINER Andrew Lau <andrew@andrewklau.com>

RUN apt-get update && apt-get install -y \
    git-core \
    nano \
    sudo \
    build-essential \
    gcc && \
    rm -rf /var/lib/apt/lists/*

RUN adduser --disabled-password --gecos "" user && \
    addgroup gpio && \
    adduser user gpio
