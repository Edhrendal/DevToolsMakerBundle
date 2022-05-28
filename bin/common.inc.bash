#!/usr/bin/env bash

set -eu

readonly BIN_SCRIPTS_PATH="${PROJECT_DIR}/bin/scripts"

readonly DOCKER_CONTAINER_APP_WORKDIR=/var/www/html

readonly DOCKER_IMAGE_PHP=dev_tools_maker/php
readonly DOCKER_PHP_DIR="${PROJECT_DIR}"/docker/php
readonly DOCKER_FILE_PATH_PHP="${DOCKER_PHP_DIR}"/Dockerfile

function titleAction() {
    printf "\e[35m${1}\e[0m\n"
}

function error() {
    printf "\e[41m${1}\e[0m\n"
}

function warning() {
    printf "\e[33m${1}\e[0m\n"
}

function buildDockerImage() {
    local DOCKER_IMAGE="${1}"
    local DOCKER_FILE_PATH="${2}"

    if [ ${isInDocker} == false ]; then
        return
    fi

    if [ "${refresh}" == true ]; then
        local refreshArguments="--no-cache --pull"
    else
        local refreshArguments=
    fi

    titleAction "Build ${DOCKER_IMAGE}"
    DOCKER_BUILDKIT=1 \
        docker \
            build \
                --file "${DOCKER_FILE_PATH}" \
                --tag="${DOCKER_IMAGE}" \
                --build-arg DOCKER_UID="$(id -u)" \
                --build-arg DOCKER_GID="$(id -g)" \
                ${refreshArguments} \
                "${PROJECT_DIR}"
}

function dockeriseImage() {
    local DOCKER_IMAGE="${1}"
    local DOCKER_FILE_PATH="${2}"

    if [ ${isInDocker} == false ]; then
        return
    fi

    if [ "${refresh}" == true ]; then
        buildDockerImage "${DOCKER_IMAGE}" "${DOCKER_FILE_PATH}"
    fi

    if [ "${isTty}" == true ]; then
        local ttyArg="--tty"
    else
        local ttyArg=
    fi

    if [ -z "${BIN_DIR-}" ]; then
        BIN_DIR="bin"
    fi

    titleAction "Run ${DOCKER_IMAGE}"
    docker \
        run \
            --rm \
            ${ttyArg} \
            --interactive \
            --volume "${PROJECT_DIR}":"${DOCKER_CONTAINER_APP_WORKDIR}" \
            --user "$(id -u)":"$(id -g)" \
            --entrypoint "${BIN_DIR}"/"$(basename "${0}")" \
            --workdir "${DOCKER_CONTAINER_APP_WORKDIR}" \
            "${DOCKER_IMAGE}" \
            ${scriptParameters}
    exit 0
}

function buildDockerPhpImage() {
    buildDockerImage "${DOCKER_IMAGE_PHP}" "${DOCKER_FILE_PATH_PHP}"
}

function dockerisePhpImage() {
    dockeriseImage "${DOCKER_IMAGE_PHP}" "${DOCKER_FILE_PATH_PHP}"
}

if [ $(which docker || false) ]; then
    readonly isInDocker=true
else
    readonly isInDocker=false
fi
export isInDocker

# Parameters
isTty=true
refresh=false
scriptParameters=
for param in "${@}"; do
    if [ "${param}" == "--no-tty" ]; then
        isTty=false
        continue
    elif [ "${param}" == "--refresh" ]; then
        refresh=true
        continue
    fi

    scriptParameters="${scriptParameters} ${param}"
done
export isTty
export refresh
export scriptParameters
