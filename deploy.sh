#!/bin/bash

rm -rf public/build
yarn build

cd src
rm -rf Filter Identifier Dataset BaseEntity.php

cp -r ~/prjTechno/mill-250/web/src/Filter ./
cp -r ~/prjTechno/mill-250/web/src/Dataset ./
cp -r ~/prjTechno/mill-250/web/src/Identifier ./
cp ~/prjTechno/mill-250/web/src/Entity/BaseEntity.php ./

cd Report
rm -rf AbstractPdf.php AbstractReport.php Downtime Event
cp -r ~/prjTechno/mill-250/web/src/Report/Event ./
cp -r ~/prjTechno/mill-250/web/src/Report/Downtime ./
cp ~/prjTechno/mill-250/web/src/Report/AbstractReport.php ./
cp ~/prjTechno/mill-250/web/src/Report/AbstractPdf.php ./

cd ../../

echo
while [ -n "$1" ]
do
case "$1" in
-f) 7z a -r doscosortweb.7z public config src templates vendor composer.json ;;
-m) 7z a -r doscosortweb.7z public config src templates composer.json ;;
esac
shift
done

mv doscosortweb.7z ~/VirtualBox\ VMs/share

cd src
rm -rf Filter Identifier Dataset  BaseEntity.php

sudo ln -s ~/prjTechno/mill-250/web/src/Filter ./
sudo ln -s ~/prjTechno/mill-250/web/src/Dataset ./
sudo ln -s ~/prjTechno/mill-250/web/src/Identifier ./
sudo ln -s ~/prjTechno/mill-250/web/src/Entity/BaseEntity.php ./

cd Report
rm -rf AbstractPdf.php AbstractReport.php Downtime Event
sudo ln -s ~/prjTechno/mill-250/web/src/Report/AbstractReport.php ./
sudo ln -s ~/prjTechno/mill-250/web/src/Report/AbstractPdf.php ./
sudo ln -s ~/prjTechno/mill-250/web/src/Report/Downtime ./
sudo ln -s ~/prjTechno/mill-250/web/src/Report/Event ./