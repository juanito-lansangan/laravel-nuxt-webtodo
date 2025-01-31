#!/bin/bash

sudo -i bash <<EOF
echo "***********************************"
echo "Navigate to the docker folder"
echo "***********************************"
cd /root/ci-deploy

echo "***********************************"
echo "Stop the application"
echo "***********************************"
docker-compose -f deploy.docker-compose.yml down

echo "***********************************"
echo "Removing all docker images"
echo "***********************************"
docker image prune -a -f

echo "***********************************"
echo "Start the application"
echo "***********************************"
docker-compose -f deploy.docker-compose.yml up -d

echo "***********************************"
echo "Application is now running!"
echo "***********************************"
EOF