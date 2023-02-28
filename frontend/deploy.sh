#!/bin/bash
#checking argument
if [ -z "$1" ]
  then
    echo "No arguments supplied"
    exit
fi

#checking branch suplied
if [ "$1" = "main" ]; then
  printf "Are you sure to deploy code in production y/n: \n"
  read confirm
  echo $confirm
  if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ] && [ "$confirm" != "yes" ] && [ "$confirm" != "Yes" ]; then
    echo "Bye"
    exit
  fi
fi

#go to deploy directory
#!/bin/bash

chmod -R 700 .nuxt
chmod -R 700 .nuxt-deploy
cd .nuxt-deploy || exit

branch=$1

git checkout "$branch"
git pull origin "$branch"

# Remove all files in the current directory
# Use caution, this will permanently delete files
rm -rf ./*

# Remove untracked files
git clean -fxd

# Copy the contents of the .nuxt directory to the current directory
cp -rf ../.nuxt/* .

# Stage the files for commit
git add .

# Commit the changes
git commit -m "autodeploy $(date '+%Y-%m-%d %H:%M:%S')"

# Push the changes to the remote branch
git push origin "$branch"



