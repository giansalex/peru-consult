#!/usr/bin/env bash
GIT_FILES=($(git show --pretty="" --name-only $CIRCLE_SHA1))
for f in ${GIT_FILES[@]};
do
  if [[ $f == "docs/"* ]] || [ $f == "mkdocs.yml" ]; then
    git config user.name "Giancarlos Salas";
    git config user.email "giansalex@gmail.com";
    git remote add gh-token "https://${GH_TOKEN}@github.com/giansalex/peru-consult.git";
    git fetch gh-token && git fetch gh-token gh-pages:gh-pages;
    pip install --upgrade pip;
    pip install -IU -r requirements.txt;
    mkdocsVersion=$(pip show mkdocs | grep Version)
    mkdocs gh-deploy -v --clean --remote-name gh-token --message "Deployed MkDocs $mkdocsVersion [ci skip]";
    break
  fi
done