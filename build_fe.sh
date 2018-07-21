#!/bin/bash

path=$(dirname "$0")

if [ $path == '.' ]
then
	path="$(pwd)"
fi

cd ./assets/read-share-fe
yarn build-dev

echo "$path/public/js/main.js"

cp -f dist/* "$path/public/js"
