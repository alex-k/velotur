#!/bin/bash


set -ex

r=alexk7898
p=velotur

b=${GIT_BRANCH:=`git rev-parse --abbrev-ref HEAD`}
c=${GIT_TAG_NAME:=${GIT_COMMIT:=`git describe --tags`}}

IFS='/' read -a arr <<< "$b"
if [[ ${arr[1]} ]]; then
    b=${arr[1]}
fi

if [ -z ${GIT_BRANCH} ]; then
  if [[ -n $(git status -s) ]]; then
    echo >&2 "You have uncommited changes in repo, will break the build"
    git status -s
#    exit 4
  fi
fi

tag_www="$r/$p:www-$c"
if [ "${1}" ]
then
    tag_www=${1}
fi


cd www
  docker build -t $tag_www .
cd ..

docker push $tag_www

