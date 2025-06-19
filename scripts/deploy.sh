#!/bin/bash

GIT_COMMIT_ID=$(git rev-parse HEAD | tr '[:upper:]' '[:lower:]')
CONTAINER_NAME="edutrack"
IMAGE_GITHUB="ghcr.io/piupiupipiii/edutrack"
IMAGE_GITHUB_TAG_VERSION="${IMAGE_GITHUB}:${GIT_COMMIT_ID}"
IMAGE_GCP="asia-southeast1-docker.pkg.dev/uascloud-462300/edutrack/edutrack"
IMAGE_GCP_TAG_VERSION="${IMAGE_GCP}:${GIT_COMMIT_ID}"

echo "Building Docker image..."
docker build --build-arg ENV_KEY="${ENV_KEY}" \
  -t "${IMAGE_GITHUB_TAG_VERSION}" \
  -t "${IMAGE_GITHUB}:latest" \
  -t "${IMAGE_GCP_TAG_VERSION}" \
  -t "${IMAGE_GCP}:latest" .

if [ $? -eq 0 ]; then
  echo "Success building image '${IMAGE_GITHUB_TAG_VERSION}'."
else
  echo "Error building Docker image. Exiting."
  exit 1
fi

echo "Checking for existing container '${CONTAINER_NAME}'..."
if docker ps -f "name=${CONTAINER_NAME}" --format "{{.ID}}" | grep -q .; then
  echo "Container '${CONTAINER_NAME}' is running. Stopping and removing..."
  docker stop "${CONTAINER_NAME}"
  docker rm "${CONTAINER_NAME}"
  echo "Container '${CONTAINER_NAME}' stopped and removed."
elif docker ps -a -f "name=${CONTAINER_NAME}" --format "{{.ID}}" | grep -q .; then
  echo "Container '${CONTAINER_NAME}' exists but is not running. Removing it."
  docker rm "${CONTAINER_NAME}"
  echo "Container '${CONTAINER_NAME}' removed."
else
  echo "Container '${CONTAINER_NAME}' not found."
fi

# Push the latest image
echo "Pushing Docker image..."
docker push "${IMAGE_GCP_TAG_VERSION}"
docker push "${IMAGE_GCP}:latest"
docker push "${IMAGE_GITHUB_TAG_VERSION}"
docker push "${IMAGE_GITHUB}:latest"
if [ $? -eq 0 ]; then
  echo "Success pushing update image '${CONTAINER_NAME}'."
else
  echo "Error pushing Docker image. Exiting."
  exit 1
fi
