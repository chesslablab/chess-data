# cd the app's root directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
APP_PATH="$(dirname $(dirname $DIR))"
cd $APP_PATH

# generate a development SSL certificate
cd docker/nginx/ssl
rm -rf chess-data.*
openssl genrsa -des3 -passout pass:foobar -out chess-data.local.pem 2048
openssl req -passin pass:foobar -new -sha256 -key chess-data.local.pem -subj "/C=US/ST=CA/O=chess-data, Inc./CN=chess-data.local" -reqexts SAN -config <(cat /etc/ssl/openssl.cnf <(printf "[SAN]\nsubjectAltName=DNS:chess-data.local,DNS:www.chess-data.local")) -out chess-data.local.csr
openssl x509 -passin pass:foobar -req -days 365 -in chess-data.local.csr -signkey chess-data.local.pem -out chess-data.local.crt
openssl rsa -passin pass:foobar -in chess-data.local.pem -out chess-data.local.key
