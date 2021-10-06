#!/usr/bin/env bash

#script variables
pluginName="LaenenMeiliSearch";
declare -a blacklistFiles=(".gitignore" "gitignore" ".DS_Store" ".git" "__MACOSX" ".zip" ".tar" ".tar.gz" ".phar" ".php_cs.dist" "phpstan.neon" "grumphp.yml" "package.sh")

# Search for latest tag
commit=$1
safecommit=$(echo "$commit" | tr /\\ _)

if [ -z ${commit} ]; then
    commit=$(git tag --sort=-creatordate | head -1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Remove old release
rm -rf $pluginName-*.zip

# Build new release
mkdir -p $pluginName
git archive ${commit} | tar -x -C $pluginName

# Remove blacklisted  files
for i in "${blacklistFiles[@]}"
do
 ( find ./$pluginName -name $i ) | xargs rm -r
done

# Create zip with tagged name
zip -r $pluginName-${safecommit}.zip $pluginName

# Remove tmp folder
rm -rf $pluginName
