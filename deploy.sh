#!/bin/bash

rm -rf public/build
yarn build
echo
while [ -n "$1" ]
do
case "$1" in
-f) 7z a doscosortweb.7z public config src templates vendor composer.json ;;
-m) 7z a doscosortweb.7z public config src templates composer.json ;;
esac
shift
done

mv doscosortweb.7z ~/VirtualBox\ VMs/Share/