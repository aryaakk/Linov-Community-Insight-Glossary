#!/bin/bash
#checking argument
if [ -z "$1" ]
  then
    echo "No arguments supplied"
    exit
fi

#checking branch suplied
# if [ $1 = "main" ]
# then
#     printf "Are you sure to deploy code in production y/n: \n"
#     read confirm
#     echo $confirm
#     if [ $confirm != "y" -o $confirm != "Y" -o $confirm != "yes" -o $confirm != "Yes" ]
#     then
#         echo "Bye"
#         exit
#     fi
# fi
#go to deploy directory
chmod -R 777 .nuxt 
chmod -R 777 .nuxt-deploy 
cd .nuxt-deploy
git checkout $1
git pull origin $1 
git rm -rf .
git clean -fxd
cp -rf ../.nuxt/. $PWD
git add .
git commit -m "autodeploy $(date '+%Y-%m-%d %H:%M')"
git push origin $1

# cd .nuxt 
# git init
# git add .
# git commit -m "autodeploy $(date '+%Y-%m-%d %H:%M')"
# git branch -m $1
# git remote add origin git@github.com:rohmanie55/linov-comunity-deploy.git
# git pull --rebase origin $1
# git checkout --theirs .
# git add .
# git commit -m "autodeploy $(date '+%Y-%m-%d %H:%M')"
# git rebase --continue
# git push origin $1


