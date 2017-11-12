#!/bin/bash


set -ex

r=alexk7898
p=velotur

b=${GIT_BRANCH:=`git rev-parse --abbrev-ref HEAD`}
c=${GIT_TAG_NAME:=${GIT_COMMIT:=`git describe --tags`}}

if [ -z ${GIT_BRANCH} ]; then
  if [[ -n $(git status -s) ]]; then
    echo >&2 "You have uncommited changes in repo, will break the build"
    git status -s

        while true; do
            read -p "Do you wish to continue?" yn
            case $yn in
                [Yy]* ) break;;
                [Nn]* ) exit;;
                * ) echo "Please answer yes or no.";;
            esac
        done
  fi
fi

b=${GIT_BRANCH:=`git rev-parse --abbrev-ref HEAD`}

IFS='/' read -a arr <<< "$b"
if [[ ${arr[1]} ]]; then
    b=${arr[1]}
fi

if [ "master" != "${b}" ]
then
    b="develop"
fi


tag_www="$r/$p:www-$b"
if [ "${1}" ]
then
    tag_www=${1}
fi


cd www
  docker build -t $tag_www .
cd ..

docker push $tag_www

