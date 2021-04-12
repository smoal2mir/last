#!/bin/bash

git add .

git commit -m "commit"

git pull

cd resources

rm -rf assets

cd ../

rm -rf node

rm -rf sql

git add .

git commit -m "commit"