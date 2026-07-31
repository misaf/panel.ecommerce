FROM docker:28-cli

RUN apk add --no-cache bash curl openssl python3

WORKDIR /app
COPY server.py /app/server.py

USER root
EXPOSE 8080
CMD ["python3", "/app/server.py"]
