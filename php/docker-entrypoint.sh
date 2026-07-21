#!/bin/sh

set -eu

state_directory="${CONTACT_STATE_DIR:-/var/lib/galactic-evil/contact}"
mkdir -p "${state_directory}/rate-limit" "${state_directory}/submissions"
chown -R www-data:www-data "${state_directory}/rate-limit" "${state_directory}/submissions"
chmod 0700 "${state_directory}/rate-limit" "${state_directory}/submissions"

exec docker-php-entrypoint "$@"
