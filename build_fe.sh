#!/bin/bash

path=$(dirname "$0")

if [ $path == '.' ]
then
	path="$(pwd)"
fi

cd ./assets/read-share-fe
yarn build-dev

cp -f dist/* "$path/public/dist"
