$GIT_COMMIT_ID=(git rev-parse HEAD).ToLower()
$CONTAINER_NAME = "edutrack"
$IMAGE = "ghcr.io/piupiupipiii/edutrack"
$IMAGE_TAG_VERSION = "$($IMAGE):$($GIT_COMMIT_ID)"

docker build --build-arg ENV_KEY="$($env:ENV_KEY)" -t "$($IMAGE_TAG_VERSION)" -t "$($IMAGE):latest" .
Write-Host "Success build image '$IMAGE_TAG_VERSION'."

try {
    # Get the container ID if it's running
    $containerId = (docker ps --filter "name=$CONTAINER_NAME" --format "{{.ID}}")

    if ($containerId) {
        Write-Host "Container '$CONTAINER_NAME' is running. Stopping and removing..."
        docker stop $containerId | Out-Null
        docker rm $containerId | Out-Null
        Write-Host "Container '$CONTAINER_NAME' stopped and removed."
    } else {
        # Check if the container exists but is stopped
        $allContainerId = (docker ps -a --filter "name=$CONTAINER_NAME" --format "{{.ID}}")
        if ($allContainerId) {
            Write-Host "Container '$CONTAINER_NAME' exists but is not running. Removing it."
            docker rm $allContainerId | Out-Null
            Write-Host "Container '$CONTAINER_NAME' removed."
        } else {
            Write-Host "Container '$CONTAINER_NAME' not found."
        }
    }
} catch {
    Write-Error "An error occurred: $($_.Exception.Message)"
}

docker push "$($IMAGE_TAG_VERSION)"
docker push "$($IMAGE):latest"
Write-Host "Success push update image '$CONTAINER_NAME'."

# Run the Docker container
docker run -d --name "$($CONTAINER_NAME)" -p 80:80 -p 5000:5000 -v ./storage:/var/www/html/storage "$($IMAGE):latest"
Write-Host "Success update container '$CONTAINER_NAME'."
