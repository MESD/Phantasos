#!/bin/bash
#
# Heavily based on https://gist.github.com/pete-otaqui/4188238
#
# Requires the existance of a file called VERSION in the same directory
if [ -f VERSION ]; then
    ORIGINAL_STRING=`cat VERSION`
    ORIGINAL_LIST=(`echo $ORIGINAL_STRING | tr '.' ' '`)
    MAJOR=${ORIGINAL_LIST[0]}
    MINOR=${ORIGINAL_LIST[1]}
    PATCH=${ORIGINAL_LIST[2]}
    echo "Current Version: $ORIGINAL_STRING"
    PATCH=$((PATCH + 1))
    SUGGESTED="$MAJOR.$MINOR.$PATCH"
    read -p "Enter a version number [$SUGGESTED]: " VERSION_INPUT
    if [ "$VERSION_INPUT" = "" ]; then
        VERSION_INPUT=$SUGGESTED
    fi
    echo "Version will be set to $VERSION_INPUT"
    echo $VERSION_INPUT > VERSION
    echo "Version $VERSION_INPUT:" > tmpfile
    git log --pretty=format:" -%s" "v$ORIGINAL_STRING"...HEAD >> tmpfile
    echo "" >> tmpfile
    cat CHANGES >> tmpfile
    mv tmpfile CHANGES
    git add CHANGES VERSION
    git commit -m "Version change to $VERSION_INPUT"
    git tag -a -m "Tagging version $VERSION_INPUT" "v$VERSION_INPUT"
    git push origin --tags
else
    echo "Could not find the VERSION file"
fi
