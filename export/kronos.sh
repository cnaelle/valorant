#!/bin/bash
case "$1" in
  "install")
      cd back &&
      composer i &&
      cp wp-config-kronos.php wp-config.php &&
      php cli/install.php &&
      wp kronos install --fresh
  ;;
  "init")
    cd back &&
    composer i &&
    cp wp-config-kronos.php wp-config.php &&
    php cli/install.php &&
    wp kronos install
  ;;
esac
