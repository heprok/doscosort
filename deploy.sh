#!/bin/bash

rm -rf public/build
yarn build

cd src
rm -rf Filter Identifier Dataset Entity/BaseEntity.php Entity/Column.php

cp -r ~/prj/mill-250/web/src/Filter ./
cp -r ~/prj/mill-250/web/src/Dataset ./
cp -r ~/prj/mill-250/web/src/Identifier ./
cp ~/prj/mill-250/web/src/Entity/BaseEntity.php ./Entity/
cp ~/prj/mill-250/web/src/Entity/Column.php ./Entity/

cd Report
rm -rf AbstractPdf.php AbstractReport.php Downtime Event
cp -r ~/prj/mill-250/web/src/Report/Event ./
cp -r ~/prj/mill-250/web/src/Report/Downtime ./
cp ~/prj/mill-250/web/src/Report/AbstractReport.php ./
cp ~/prj/mill-250/web/src/Report/AbstractPdf.php ./

cd ../../

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

cd src
rm -rf Identifier

sudo ln -s ~/prj/mill-250/web/src/Identifier ./