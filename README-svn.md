# Monocle Wordpress Plugin SVN

Wordpress manages plugin releases in a subversion repo.  Do not use it for development, only releases should get pushed there.  Mostly documenting this because I do not use SVN regularly and will forget.

There is a detailed but possibly overcomplicated guide here
https://learnwithdaniel.com/2019/09/publishing-your-first-wordpress-plugin-with-git-and-svn/


## Checkout the current SVN repo
```
svn checkout --depth immediates https://plugins.svn.wordpress.org/monocle svn
```

## Make any changes to files in /assets and /trunk

Currently this involves just copying files from /src into the SVN locations
```
cp src/assets/*.png svn/assets/.
cp src/* svn/trunk/.
```
If there are NEW files, don't forget to `svn add` them.

## Create a tag
```
cd svn
svn cp "trunk" "tags/1.0.99"
```

## Commit and push changes
```
svn ci -m 'commit message here' --username monocleintegrations
```